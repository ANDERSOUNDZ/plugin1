<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Exception;

class ValidationException extends \RuntimeException
{
    private array $errors;

    public function __construct(array $errors)
    {
        parent::__construct(implode('; ', $errors));
        $this->errors = $errors;
    }

    public function errors(): array { return $this->errors; }
}
