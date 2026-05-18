<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\ValueObject;

use Bukets\Contifico\Core\Exception\InvalidIvaException;

final class Iva
{
    private const VALID_PERCENTAGES = [0, 5, 12, 14, 15];

    private int $percentage;

    public function __construct(int $percentage)
    {
        if (!in_array($percentage, self::VALID_PERCENTAGES, true)) {
            throw new InvalidIvaException(
                sprintf('Invalid IVA percentage: %d. Valid: %s', $percentage, implode(', ', self::VALID_PERCENTAGES))
            );
        }
        $this->percentage = $percentage;
    }

    public static function cero(): self
    {
        return new self(0);
    }

    public static function doce(): self
    {
        return new self(12);
    }

    public static function quince(): self
    {
        return new self(15);
    }

    public function percentage(): int { return $this->percentage; }

    public function asDecimal(): float
    {
        return $this->percentage / 100;
    }

    public function equals(self $other): bool
    {
        return $this->percentage === $other->percentage;
    }

    public function isZero(): bool
    {
        return $this->percentage === 0;
    }
}
