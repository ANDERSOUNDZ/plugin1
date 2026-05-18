<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Port;

use Bukets\Contifico\Core\Domain\Entity\Order;

interface OrderRepositoryPort
{
    public function findById(int $orderId): ?Order;
    public function getCompletedOrdersSince(\DateTimeImmutable $since): array;
    public function markAsSynced(int $orderId): bool;
    public function isSynced(int $orderId): bool;
}
