<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tenants\Tests\Unit\Shared\Domain\ValueObject\Mother\NanoIdMother;

final class AggregateRootTest extends TestCase
{
    #[Test]
    public function should_pull_domain_events_and_clear_aggregate_root(): void
    {
        $emptyAggregateRoot = new FakeAggregateRoot(NanoIdMother::random()->value(), 'Ivan');
        $emptyAggregateRoot->changeName('Saga');
        $emptyAggregateRoot->changeName('Ivan');

        $domainEvents = $emptyAggregateRoot->pullDomainEvents();

        $this->assertCount(2, $domainEvents);
    }
}
