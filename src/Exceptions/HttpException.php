<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Exceptions;

use Exception;
use Throwable;

class HttpException extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?array $context = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
