<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\Bus\Query;

use DomainException;

use function sprintf;

final class UnhandledQueryException extends DomainException
{
    public const string NO_ASSOCIATED_HANDLER_MESSAGE = 'No handler found for Query: <%s>';

    public static function fromQuery(Query $query): self
    {
        return new self(sprintf(self::NO_ASSOCIATED_HANDLER_MESSAGE, $query::class));
    }
}
