<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\Bus\Query;

use Tenants\Shared\Domain\Bus\Query\QueryResponse;

final readonly class EmptyQueryResponse implements QueryResponse
{
    public const string DEFAULT_RESPONSE_VALUE = 'ok';

    public function __construct(
        private string $response,
    ) {}

    public function response(): string
    {
        return $this->response;
    }
}
