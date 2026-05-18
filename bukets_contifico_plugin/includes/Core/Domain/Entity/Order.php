<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Entity;

use Bukets\Contifico\Core\Domain\ValueObject\Money;

final class Order
{
    private int $id;
    private string $status;
    private Customer $customer;
    private Money $total;
    private Money $shippingTotal;
    private string $paymentMethod;
    private array $items;
    private \DateTimeImmutable $createdAt;

    public function __construct(
        int $id,
        string $status,
        Customer $customer,
        Money $total,
        Money $shippingTotal,
        string $paymentMethod,
        array $items = [],
        ?\DateTimeImmutable $createdAt = null,
    ) {
        $this->id = $id;
        $this->status = $status;
        $this->customer = $customer;
        $this->total = $total;
        $this->shippingTotal = $shippingTotal;
        $this->paymentMethod = $paymentMethod;
        $this->items = $items;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
    }

    public function id(): int { return $this->id; }
    public function status(): string { return $this->status; }
    public function customer(): Customer { return $this->customer; }
    public function total(): Money { return $this->total; }
    public function shippingTotal(): Money { return $this->shippingTotal; }
    public function paymentMethod(): string { return $this->paymentMethod; }
    public function items(): array { return $this->items; }

    public function isCompleted(): bool
    {
        return in_array($this->status, ['completed', 'processing'], true);
    }
}
