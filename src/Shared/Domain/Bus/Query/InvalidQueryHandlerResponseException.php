<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\Bus\Query;

use DomainException;

use function sprintf;

final class InvalidQueryHandlerResponseException extends DomainException
{
    public const string INVALID_QUERY_HANDLER_RESPONSE_MESSAGE = '
        Invalid response for Query <%s>: Expected QueryResponse (or null) but got <%s>
    ';

    public static function fromQuery(mixed $queryType, Query $query): self
    {
        $queryType = get_debug_type($queryType);

        return new self(sprintf(self::INVALID_QUERY_HANDLER_RESPONSE_MESSAGE, $query::class, $queryType));
    }
}
