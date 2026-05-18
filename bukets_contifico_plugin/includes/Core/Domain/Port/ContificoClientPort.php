<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Port;

interface ContificoClientPort
{
    public function testConnection(): bool;
    public function getCategories(): array;
    public function getProducts(int $page = 1, int $size = 100, array $filters = []): array;
    public function getProductStock(string $productId): array;
    public function getWarehouses(): array;
    public function searchPerson(string $search): ?\Bukets\Contifico\Core\Domain\Entity\Customer;
    public function createPerson(\Bukets\Contifico\Core\Domain\Entity\Customer $customer): ?\Bukets\Contifico\Core\Domain\Entity\Customer;
    public function createDocument(array $data): ?array;
    public function getDocumentStatus(string $id): ?array;
    public function registerPayment(string $documentId, array $data): bool;
    public function createInventoryMovement(array $data): bool;
}
