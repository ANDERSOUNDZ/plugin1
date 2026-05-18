<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Service;

use Bukets\Contifico\Core\Domain\Port\ContificoClientPort;
use Bukets\Contifico\Core\Domain\Port\InvoiceRepositoryPort;
use Bukets\Contifico\Core\Domain\Port\LoggerPort;
use Bukets\Contifico\Core\Domain\Port\SettingsPort;
use Bukets\Contifico\Core\Domain\Entity\Invoice;
use Bukets\Contifico\Core\Domain\Entity\InvoiceLine;
use Bukets\Contifico\Core\Domain\Entity\Customer;
use Bukets\Contifico\Core\Domain\ValueObject\Money;
use Bukets\Contifico\Core\Domain\ValueObject\Iva;

final class InvoiceGenerationService
{
    private const SERIE_DEFAULT = '001-881';
    private const IVA_DEFAULT   = 15;

    private ContificoClientPort $api;
    private InvoiceRepositoryPort $invoiceRepo;
    private LoggerPort $logger;
    private SettingsPort $settings;

    public function __construct(
        ContificoClientPort $api,
        InvoiceRepositoryPort $invoiceRepo,
        LoggerPort $logger,
        SettingsPort $settings
    ) {
        $this->api         = $api;
        $this->invoiceRepo = $invoiceRepo;
        $this->logger      = $logger;
        $this->settings    = $settings;
    }

    public function generateFromOrder(
        int $orderId,
        Customer $customer,
        array $orderItems,
        Money $shippingTotal,
        string $paymentMethod
    ): ?Invoice {
        $serie       = (string) $this->settings->get('serie', self::SERIE_DEFAULT);
        $ivaPct      = (int) $this->settings->get('iva', self::IVA_DEFAULT);
        $centroCosto = (string) $this->settings->get('centro_costo_id', '');
        $envioProdId = (string) $this->settings->get('envio_producto_id', '');

        $cliente = $this->findOrCreateCustomer($customer);
        if ($cliente === null) {
            return null;
        }

        $lines = $this->buildLines($orderItems, $serie, $ivaPct, $envioProdId, $shippingTotal);
        if (empty($lines)) {
            return null;
        }

        $fecha   = new \DateTimeImmutable();
        $invoice = $this->buildInvoice($orderId, $serie, $centroCosto, $cliente, $lines, $fecha);
        $docData = $this->buildDocumentPayload($invoice, $cliente);

        $result = $this->api->createDocument($docData);
        if ($result === null || isset($result['error'])) {
            $this->logger->error('Error creating invoice in Contifico', array(
                'order_id'  => $orderId,
                'response'  => $result,
            ));
            return null;
        }

        $saved = new Invoice(
            $result['id'] ?? '',
            $serie,
            $result['documento'] ?? $invoice->documento(),
            $fecha,
            'FAC',
            'CLI',
            $cliente,
            $lines,
            "Pedido Bukets #{$orderId}",
            "BK-ORDER-{$orderId}",
            $centroCosto
        );
        $this->invoiceRepo->save($saved, $orderId, $result);
        $this->registerPayment($result['id'] ?? '', $saved->total(), $fecha, $paymentMethod);
        $this->logger->info('Invoice created successfully', array(
            'order_id'  => $orderId,
            'documento' => $saved->documento(),
            'total'     => $saved->total()->asFloat(),
        ));
        return $saved;
    }

    private function buildInvoice(int $orderId, string $serie, string $centroCosto, Customer $cliente, array $lines, \DateTimeImmutable $fecha): Invoice
    {
        $lastNum   = $this->invoiceRepo->getLastDocumentNumber($serie);
        $nextNum   = $lastNum + 1;
        $documento = $serie . '-' . str_pad((string) $nextNum, 9, '0', STR_PAD_LEFT);
        return new Invoice(
            '',
            $serie,
            $documento,
            $fecha,
            'FAC',
            'CLI',
            $cliente,
            $lines,
            "Pedido Bukets #{$orderId}",
            "BK-ORDER-{$orderId}",
            $centroCosto
        );
    }

    private function buildDocumentPayload(Invoice $invoice, Customer $cliente): array
    {
        return array(
            'pos'              => $this->settings->get('pos_token', ''),
            'fecha_emision'    => $invoice->fechaEmision()->format('d/m/Y'),
            'hora_emision'     => $invoice->fechaEmision()->format('H:i:s'),
            'tipo_documento'   => 'FAC',
            'tipo_registro'    => 'CLI',
            'documento'        => $invoice->documento(),
            'descripcion'      => $invoice->descripcion(),
            'referencia'       => $invoice->referencia(),
            'electronico'      => true,
            'estado'           => 'P',
            'cliente'          => array(
                'cedula'      => $cliente->identificacion(),
                'razon_social' => $cliente->razonSocial(),
                'tipo'        => $cliente->tipo(),
            ),
            'detalles'         => $this->buildDetalles($invoice),
            'subtotal_0'       => $invoice->subtotal0()->asFloat(),
            'subtotal_12'      => $invoice->subtotal12()->asFloat(),
            'iva'              => $invoice->iva()->asFloat(),
            'centro_costo_id'  => $invoice->centroCostoId(),
            'total'            => $invoice->total()->asFloat(),
        );
    }

    private function buildDetalles(Invoice $invoice): array
    {
        $items = array();
        foreach ($invoice->detalles() as $line) {
            $esCero = $line->iva()->isZero();
            $items[] = array(
                'producto_id'         => $line->productoId(),
                'serie'               => $line->serie(),
                'descripcion'         => $line->descripcion(),
                'cantidad'            => $line->cantidad(),
                'precio'              => $line->precioUnitario()->asFloat(),
                'porcentaje_iva'      => $line->iva()->percentage(),
                'base_gravable'       => $line->baseGravable()->asFloat(),
                'base_cero'           => $esCero ? $line->subtotal()->asFloat() : 0,
                'base_no_gravable'    => 0,
                'porcentaje_descuento' => 0,
            );
        }
        return $items;
    }

    private function findOrCreateCustomer(Customer $customer): ?Customer
    {
        $identificacion = $customer->identificacion();
        if (empty($identificacion)) {
            return null;
        }
        $found = $this->api->searchPerson($identificacion);
        if ($found !== null) {
            return $found;
        }
        return $this->api->createPerson($customer);
    }

    private function buildLines(array $orderItems, string $serie, int $ivaPct, string $envioProdId, Money $shippingTotal): array
    {
        $lines = array();
        $iva   = new Iva($ivaPct);
        foreach ($orderItems as $item) {
            if (empty($item['sku'])) {
                continue;
            }
            $lines[] = new InvoiceLine(
                $item['contifico_product_id'] ?? '',
                $serie,
                $item['nombre'] ?? '',
                (float) ($item['cantidad'] ?? 1),
                Money::fromFloat((float) ($item['precio'] ?? 0)),
                $iva
            );
        }
        if ($shippingTotal->asCents() > 0 && !empty($envioProdId)) {
            $lines[] = new InvoiceLine(
                $envioProdId,
                $serie,
                'Costo de Envio',
                1,
                $shippingTotal,
                Iva::cero()
            );
        }
        return $lines;
    }

    private function registerPayment(string $docId, Money $total, \DateTimeImmutable $fecha, string $paymentMethod): void
    {
        if (empty($docId)) {
            return;
        }
        $formaCobroMap = $this->settings->get('forma_cobro_map', array());
        $config        = $formaCobroMap[$paymentMethod] ?? array('codigo' => 'TC', 'tipo_ping' => 'D');
        $data = array(
            'forma_cobro' => $config['codigo'],
            'monto'       => $total->asFloat(),
            'fecha'       => $fecha->format('d/m/Y'),
            'numero_comprobante' => 'TXN-BK-' . $fecha->getTimestamp(),
        );
        if (!empty($config['tipo_ping'])) {
            $data['tipo_ping'] = $config['tipo_ping'];
        }
        $this->api->registerPayment($docId, $data);
    }
}
