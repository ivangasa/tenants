<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\Bus\Query;

use Tenants\Shared\Domain\Bus\Query\QueryHandler;

final class EmptyQueryHandler implements QueryHandler
{
    public function __invoke(EmptyQuery $query): EmptyQueryResponse
    {
        return new EmptyQueryResponse(EmptyQueryResponse::DEFAULT_RESPONSE_VALUE);
    }
}
