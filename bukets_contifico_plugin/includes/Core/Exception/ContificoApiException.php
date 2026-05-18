<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Exception;

class ContificoApiException extends \RuntimeException
{
    private int $httpStatus;
    private array $responseBody;

    public function __construct(string $message, int $httpStatus = 0, array $responseBody = array(), ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->httpStatus   = $httpStatus;
        $this->responseBody = $responseBody;
    }

    public function httpStatus(): int { return $this->httpStatus; }
    public function responseBody(): array { return $this->responseBody; }
}
