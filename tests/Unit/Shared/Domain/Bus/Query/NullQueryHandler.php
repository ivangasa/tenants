<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\Bus\Query;

use Tenants\Shared\Domain\Bus\Query\QueryHandler;

final class NullQueryHandler implements QueryHandler
{
    public function __invoke(EmptyQuery $query): null
    {
        return null;
    }
}
