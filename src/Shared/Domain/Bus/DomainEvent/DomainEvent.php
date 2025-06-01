<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\Bus\DomainEvent;

use DateTimeImmutable;
use DateTimeInterface;
use JsonSerializable;
use Override;
use Tenants\Shared\Domain\Utils;
use Tenants\Shared\Domain\ValueObject\NanoId;

abstract class DomainEvent implements JsonSerializable
{
    public const string AGGREGATE_ID_KEY = 'aggregateId';
    public const string OCCURRED_ON_KEY = 'occurredOn';
    public const string DOMAIN_EVENT_ID_KEY = 'domainEventId';
    public const string BODY_KEY = 'body';
    public const string DOMAIN_EVENT_NAME_KEY = 'domainEventName';

    protected NanoId $aggregateId;
    protected NanoId $domainEventId;
    protected string $occurredOn;

    public function __construct(string $aggregateId, ?string $domainEventId = null, ?string $occurredOn = null)
    {
        $datetime = null !== $occurredOn
            ? DateTimeImmutable::createFromFormat(Utils::DATE_FORMAT, $occurredOn)
            : null;

        $this->aggregateId = NanoId::fromString($aggregateId);
        $this->domainEventId = NanoId::fromString($domainEventId ?? NanoId::random()->value());
        $this->occurredOn = Utils::dateTimeToString($datetime instanceof DateTimeInterface ? $datetime : null);
    }

    final public function aggregateId(): NanoId
    {
        return $this->aggregateId;
    }

    final public function domainEventId(): NanoId
    {
        return $this->domainEventId;
    }

    final public function occurredOn(): string
    {
        return $this->occurredOn;
    }

    /** @return array<string, mixed> */
    #[Override]
    final public function jsonSerialize(): iterable
    {
        return [
            self::DOMAIN_EVENT_NAME_KEY => static::domainEventName(),
            self::DOMAIN_EVENT_ID_KEY => $this->domainEventId->value(),
            self::AGGREGATE_ID_KEY => $this->aggregateId->value(),
            self::OCCURRED_ON_KEY => $this->occurredOn,
            self::BODY_KEY => static::toPrimitives(),
        ];
    }

    /** @param array<string, mixed> $body */
    abstract public static function fromPrimitives(
        string $aggregateId,
        array $body = [],
        ?string $domainEventId = null,
        ?string $occurredOn = null,
    ): self;

    /** @return array<string, mixed> */
    abstract public function toPrimitives(): iterable;

    abstract public static function domainEventName(): string;
}
