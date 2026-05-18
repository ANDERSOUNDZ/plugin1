<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\ValueObject;

use Bukets\Contifico\Core\Exception\InvalidMoneyException;

final class Money
{
    private int $cents;

    private function __construct(int $cents)
    {
        if ($cents < 0) {
            throw new InvalidMoneyException('Money cannot be negative');
        }
        $this->cents = $cents;
    }

    public static function fromInt(int $cents): self
    {
        return new self($cents);
    }

    public static function fromFloat(float $amount): self
    {
        return new self((int) round($amount * 100));
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public function asCents(): int { return $this->cents; }

    public function asFloat(): float
    {
        return $this->cents / 100;
    }

    public function add(self $other): self
    {
        return new self($this->cents + $other->cents);
    }

    public function subtract(self $other): self
    {
        return new self($this->cents - $other->cents);
    }

    public function multiply(float $factor): self
    {
        return new self((int) round($this->cents * $factor));
    }

    public function equals(self $other): bool
    {
        return $this->cents === $other->cents;
    }

    public function greaterThan(self $other): bool
    {
        return $this->cents > $other->cents;
    }

    public function format(): string
    {
        return number_format($this->asFloat(), 2, '.', ',');
    }
}
