<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\Bus\DomainEvent;

use Override;
use Tenants\Shared\Domain\Bus\DomainEvent\DomainEvent;

final class FakeDomainEvent extends DomainEvent
{
    public const string DOMAIN_EVENT_NAME = 'fake.domain_event';

    public function __construct(
        string $aggregateId,
        private readonly string $name,
        ?string $domainEventId = null,
        ?string $occurredOn = null,
    ) {
        parent::__construct($aggregateId, $domainEventId, $occurredOn);
    }

    #[Override]
    public static function fromPrimitives(
        string $aggregateId,
        array $body = [],
        ?string $domainEventId = null,
        ?string $occurredOn = null,
    ): self {
        // @phpstan-ignore argument.type
        return new self($aggregateId, $body['name'], $domainEventId, $occurredOn);
    }

    /** @return array<string, mixed> */
    public function toPrimitives(): iterable
    {
        return [
            'aggregateId' => $this->aggregateId,
            'name' => $this->name,
        ];
    }

    public static function domainEventName(): string
    {
        return self::DOMAIN_EVENT_NAME;
    }
}
