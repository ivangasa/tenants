<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain;

use JsonSerializable;
use Tenants\Shared\Domain\Bus\DomainEvent\DomainEvent;

abstract class AggregateRoot implements JsonSerializable
{
    /** @var list<DomainEvent> */
    private array $domainEvents = [];

    /** @return list<DomainEvent> */
    final public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function recordThat(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    /** @return array<string, mixed> */
    abstract public function jsonSerialize(): array;
}
