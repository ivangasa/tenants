<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\Bus\DomainEvent;

use DomainException;

use function sprintf;

final class NotFoundDomainEventException extends DomainException
{
    public const string NOT_FOUND_DOMAIN_EVENT_MESSAGE = 'Domain event with name <%s> was not found';

    public static function fromDomainEventName(string $domainEventName): self
    {
        return new self(sprintf(self::NOT_FOUND_DOMAIN_EVENT_MESSAGE, $domainEventName));
    }
}
