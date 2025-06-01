<?php

declare(strict_types=1);

namespace Tenants\Shared\Infrastructure\Symfony\Exception;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Tenants\Shared\Domain\Bus\DomainEvent\NotFoundDomainEventException;
use Throwable;

final class ExceptionToHttpStatusMapper
{
    public const int DEFAULT_STATUS_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;

    /** @var array<class-string<Throwable>, int> */
    private array $exceptions = [
        InvalidArgumentException::class => Response::HTTP_BAD_REQUEST,
        NotFoundHttpException::class => Response::HTTP_NOT_FOUND,
        UnprocessableEntityHttpException::class => Response::HTTP_BAD_REQUEST,
        NotFoundDomainEventException::class => Response::HTTP_NOT_FOUND,
    ];

    /** @param class-string<Throwable> $exceptionClass */
    public function register(string $exceptionClass, int $statusCode): void
    {
        $this->exceptions[$exceptionClass] = $statusCode;
    }

    /** @param class-string<Throwable> $exceptionClass */
    public function statusCodeFor(string $exceptionClass): int
    {
        return $this->exceptions[$exceptionClass] ?? self::DEFAULT_STATUS_CODE;
    }

    public function toMap(Throwable $exception): int
    {
        foreach ($this->exceptions as $class => $statusCode) {
            if ($exception instanceof $class) {
                return $statusCode;
            }
        }

        return self::DEFAULT_STATUS_CODE;
    }
}
