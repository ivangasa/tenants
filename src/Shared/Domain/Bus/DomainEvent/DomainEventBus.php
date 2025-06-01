<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\Bus\DomainEvent;

interface DomainEventBus
{
    public function publish(DomainEvent ...$domainEvents): void;
}
