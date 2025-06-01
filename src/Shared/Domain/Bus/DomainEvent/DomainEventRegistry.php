<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\Bus\DomainEvent;

use function array_key_exists;

final readonly class DomainEventRegistry
{
    /** @param array<DomainEvent::EVENT_NAME,class-string<DomainEvent>> $domainEvents */
    public function __construct(
        private array $domainEvents,
    ) {}

    public function __invoke(string $domainEventName): string
    {
        if (!array_key_exists($domainEventName, $this->domainEvents)) {
            throw NotFoundDomainEventException::fromDomainEventName($domainEventName);
        }

        return $this->domainEvents[$domainEventName];
    }
}
