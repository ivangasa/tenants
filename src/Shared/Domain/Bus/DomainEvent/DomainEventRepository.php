<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\Bus\DomainEvent;

interface DomainEventRepository
{
    public function save(DomainEvent $domainEvent): void;
}
