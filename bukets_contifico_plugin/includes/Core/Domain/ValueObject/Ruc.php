<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\ValueObject;

use Bukets\Contifico\Core\Exception\InvalidRucException;

final class Ruc
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);
        if (!self::isValid($value)) {
            throw new InvalidRucException(sprintf('Invalid RUC: %s', $value));
        }
        $this->value = $value;
    }

    public function value(): string { return $this->value; }

    public static function isValid(string $ruc): bool
    {
        if (!preg_match('/^\d{13}$/', $ruc)) {
            return false;
        }
        $tercerDigito = (int) $ruc[2];
        if ($tercerDigito !== 9 && $tercerDigito !== 6) {
            $cedula = substr($ruc, 0, 10);
            if (!Cedula::isValid($cedula)) {
                return false;
            }
        }
        $establecimiento = substr($ruc, 10, 3);
        if ($establecimiento === '000') {
            return false;
        }
        return true;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
