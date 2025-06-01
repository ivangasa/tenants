<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\Bus\DomainEvent;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tenants\Shared\Domain\Utils;
use Tenants\Tests\Unit\Shared\Domain\ValueObject\Mother\MotherCreator;
use Tenants\Tests\Unit\Shared\Domain\ValueObject\Mother\NanoIdMother;

use const DATE_ATOM;

final class DomainEventTest extends TestCase
{
    #[Test]
    public function should_instantiate_the_domain_event(): void
    {
        $aggregateId = NanoIdMother::random()->value();
        $name = MotherCreator::random()->name();
        $domainEventId = NanoIdMother::random()->value();
        $occurredOn = MotherCreator::random()->dateTime()->format(DATE_ATOM);

        $domainEvent = new FakeDomainEvent($aggregateId, $name, $domainEventId, $occurredOn);

        $this->assertSame(FakeDomainEvent::DOMAIN_EVENT_NAME, $domainEvent::domainEventName());
        $this->assertSame($aggregateId, $domainEvent->aggregateId()->value());
        $this->assertSame($domainEventId, $domainEvent->domainEventId()->value());
        $this->assertSame($occurredOn, $domainEvent->occurredOn());
    }

    #[Test]
    public function should_use_given_datetime_when_instantiating_domain_event(): void
    {
        $aggregateId = NanoIdMother::random()->value();
        $name = MotherCreator::random()->name();
        $occurredOn = new DateTimeImmutable()
            ->format(DATE_ATOM);

        $domainEvent = new FakeDomainEvent($aggregateId, $name, null, $occurredOn);

        $this->assertSame($occurredOn, $domainEvent->occurredOn());
    }

    #[Test]
    public function should_not_use_given_datetime_if_format_is_incorrect(): void
    {
        $aggregateId = NanoIdMother::random()->value();
        $name = MotherCreator::random()->name();
        $occurredOn = 'invalid-date';

        $domainEvent = new FakeDomainEvent($aggregateId, $name, null, $occurredOn);

        $this->assertNotSame($occurredOn, $domainEvent->occurredOn());
    }

    #[Test]
    public function should_serialize_the_domain_event(): void
    {
        $aggregateId = NanoIdMother::random()->value();
        $name = MotherCreator::random()->name();
        $domainEventId = NanoIdMother::random()->value();
        $occurredOn = new DateTimeImmutable()
            ->format(Utils::DATE_FORMAT);

        $domainEvent = new FakeDomainEvent($aggregateId, $name, $domainEventId, $occurredOn);
        $serialized = $domainEvent->jsonSerialize();

        $this->assertIsArray($serialized);
        $this->assertSame(FakeDomainEvent::DOMAIN_EVENT_NAME, $serialized['domainEventName']);
        $this->assertSame($aggregateId, $serialized['aggregateId']);
        $this->assertSame($domainEventId, $serialized['domainEventId']);
        $this->assertSame($occurredOn, $serialized['occurredOn']);
    }
}
