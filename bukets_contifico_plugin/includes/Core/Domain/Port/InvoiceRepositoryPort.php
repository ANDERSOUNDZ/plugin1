<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Port;

use Bukets\Contifico\Core\Domain\Entity\Invoice;

interface InvoiceRepositoryPort
{
    public function save(Invoice $invoice, int $orderId = 0, array $rawResponse = []): Invoice;
    public function findById(string $id): ?Invoice;
    public function findByDocumento(string $documento): ?Invoice;
    public function getLastDocumentNumber(string $serie): int;
    public function updateSriStatus(string $documentId, string $status): bool;
}
