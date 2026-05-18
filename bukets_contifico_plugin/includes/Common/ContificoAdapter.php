<?php
declare(strict_types=1);

namespace Bukets\Contifico\Common;

use Bukets\Contifico\Core\Domain\Port\ContificoClientPort;
use Bukets\Contifico\Core\Domain\Port\SettingsPort;
use Bukets\Contifico\Core\Domain\Entity\Customer;
use Bukets\Contifico\Core\Domain\ValueObject\Cedula;

final class ContificoAdapter implements ContificoClientPort
{
    private string $apiKey;
    private string $posToken;
    private string $apiBase;
    private const TIMEOUT = 30;
    private const API_BASE_DEFAULT = 'https://api.contifico.com/sistema/api/v2';

    public function __construct(SettingsPort $settings)
    {
        $this->apiKey   = (string) $settings->get('api_key', '');
        $this->posToken = (string) $settings->get('pos_token', '');
        $this->apiBase  = (string) $settings->get('api_base', self::API_BASE_DEFAULT);
    }

    public function testConnection(): bool
    {
        $result = $this->request('GET', 'producto/', array('result_size' => 1));
        return !isset($result['error']);
    }

    public function getCategories(): array
    {
        return $this->request('GET', 'categoria/');
    }

    public function getProducts(int $page = 1, int $size = 100, array $filters = array()): array
    {
        $query = array_merge(array('page' => $page, 'result_size' => $size), $filters);
        return $this->request('GET', 'producto/', $query);
    }

    public function getProductStock(string $productId): array
    {
        $result = $this->request('GET', "producto/{$productId}/stock/");
        return isset($result['error']) ? array() : $result;
    }

    public function getWarehouses(): array
    {
        return $this->request('GET', 'bodega/');
    }

    public function searchPerson(string $search): ?Customer
    {
        $result = $this->request('GET', 'persona/', array('search' => $search));
        if (isset($result['error'])) {
            return null;
        }
        $items = $result['results'] ?? array();
        if (empty($items)) {
            return null;
        }
        $data = $items[0];
        return new Customer(
            (string) ($data['id'] ?? ''),
            (string) ($data['razon_social'] ?? ''),
            (string) ($data['tipo'] ?? 'N'),
            !empty($data['cedula']) ? new Cedula($data['cedula']) : null,
            null,
            (string) ($data['email'] ?? ''),
            (string) ($data['telefono'] ?? ''),
            (string) ($data['direccion'] ?? '')
        );
    }

    public function createPerson(Customer $customer): ?Customer
    {
        $data = array(
            'cedula'      => $customer->identificacion(),
            'razon_social' => $customer->razonSocial(),
            'tipo'        => $customer->tipo(),
            'email'       => $customer->email(),
            'telefonos'   => $customer->telefono(),
            'direccion'   => $customer->direccion(),
            'es_cliente'  => true,
        );

        $pos = $this->posToken;
        $result = $this->request('POST', "persona/?pos=" . urlencode($pos), $data);

        if (isset($result['error']) || !isset($result['id'])) {
            return null;
        }

        return new Customer(
            (string) ($result['id'] ?? ''),
            $customer->razonSocial(),
            $customer->tipo(),
            $customer->cedula(),
            null,
            $customer->email()
        );
    }

    public function createDocument(array $data): ?array
    {
        $result = $this->request('POST', 'documento/', $data);
        return isset($result['error']) ? null : $result;
    }

    public function getDocumentStatus(string $id): ?array
    {
        $result = $this->request('GET', "documento/estado/{$id}/");
        return isset($result['error']) ? null : $result;
    }

    public function registerPayment(string $documentId, array $data): bool
    {
        $result = $this->request('POST', "documento/{$documentId}/cobro/", $data);
        return !isset($result['error']);
    }

    public function createInventoryMovement(array $data): bool
    {
        $result = $this->request('POST', 'movimiento-inventario/', $data);
        return !isset($result['error']);
    }

    private function request(string $method, string $endpoint, ?array $data = null, array $query = array()): array
    {
        $url = $this->apiBase . '/' . ltrim($endpoint, '/');

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        $args = array(
            'method'    => $method,
            'timeout'   => self::TIMEOUT,
            'headers'   => array(
                'Authorization' => $this->apiKey,
                'Content-Type'  => 'application/json',
            ),
        );

        if ($data !== null && in_array($method, array('POST', 'PUT'), true)) {
            $args['body'] = wp_json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            return array('error' => $response->get_error_message(), 'http' => 0);
        }

        $status = wp_remote_retrieve_response_code($response);
        $body   = json_decode(wp_remote_retrieve_body($response), true) ?? array();

        if ($status < 200 || $status >= 300) {
            $msg = $body['detail'] ?? $body['message'] ?? "HTTP {$status}";
            return array('error' => $msg, 'http' => $status, 'body' => $body);
        }

        return $body ?? array();
    }
}
