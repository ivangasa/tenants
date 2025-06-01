<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\Bus\DomainEvent;

use LogicException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Tenants\Shared\Domain\Bus\DomainEvent\DomainEventRegistry;
use Tenants\Shared\Domain\Bus\DomainEvent\NotFoundDomainEventException;
use Tenants\Shared\Infrastructure\Symfony\Exception\ExceptionToHttpStatusMapper;

final class DomainEventRegistryTest extends TestCase
{
    #[Test]
    public function should_return_the_class_name_if_event_exists(): void
    {
        $domainEventRegistry = new DomainEventRegistry([
            FakeDomainEvent::DOMAIN_EVENT_NAME => FakeDomainEvent::class,
        ]);

        $fakeDomainEventRegistry = $domainEventRegistry(FakeDomainEvent::DOMAIN_EVENT_NAME);
        $this->assertSame(FakeDomainEvent::class, $fakeDomainEventRegistry);
    }

    #[Test]
    public function should_throw_exception_if_event_does_not_exist(): void
    {
        $this->expectException(NotFoundDomainEventException::class);
        $this->expectExceptionMessage('fake.unknown_event');

        $registry = new DomainEventRegistry([]);
        $registry('fake.unknown_event');
    }

    #[Test]
    public function should_return_status_code_for_known_exception(): void
    {
        $mapper = new ExceptionToHttpStatusMapper();
        $mapper->register(NotFoundDomainEventException::class, Response::HTTP_NOT_FOUND);

        $this->assertSame(Response::HTTP_NOT_FOUND, $mapper->toMap(new NotFoundDomainEventException()));
        $this->assertSame(Response::HTTP_NOT_FOUND, $mapper->statusCodeFor(NotFoundDomainEventException::class));
    }

    #[Test]
    public function should_return_default_status_code_for_unmapped_exception(): void
    {
        $mapper = new ExceptionToHttpStatusMapper();
        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $mapper->toMap(new LogicException()));
    }
}
