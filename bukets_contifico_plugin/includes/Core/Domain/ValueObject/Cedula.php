<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\ValueObject;

use Bukets\Contifico\Core\Exception\InvalidCedulaException;

final class Cedula
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);
        if (!self::isValid($value)) {
            throw new InvalidCedulaException(sprintf('Invalid cedula: %s', $value));
        }
        $this->value = $value;
    }

    public function value(): string { return $this->value; }

    public static function isValid(string $cedula): bool
    {
        if (!preg_match('/^\d{10}$/', $cedula)) {
            return false;
        }
        $provincia = (int) substr($cedula, 0, 2);
        if ($provincia < 1 || $provincia > 24) {
            return false;
        }
        $tercerDigito = (int) $cedula[2];
        if ($tercerDigito > 5) {
            return false;
        }
        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $total = 0;
        for ($i = 0; $i < 9; $i++) {
            $valor = (int) $cedula[$i] * $coeficientes[$i];
            $total += $valor >= 10 ? $valor - 9 : $valor;
        }
        $digitoVerificador = (int) $cedula[9];
        $resultado = (10 - ($total % 10)) % 10;
        return $resultado === $digitoVerificador;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
