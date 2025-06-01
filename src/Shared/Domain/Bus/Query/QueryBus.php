<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\Bus\Query;

interface QueryBus
{
    public function ask(Query $query): ?QueryResponse;
}
