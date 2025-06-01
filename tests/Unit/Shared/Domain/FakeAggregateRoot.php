<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain;

use Tenants\Shared\Domain\AggregateRoot;
use Tenants\Tests\Unit\Shared\Domain\Bus\DomainEvent\FakeDomainEvent;

final class FakeAggregateRoot extends AggregateRoot
{
    public function __construct(
        private string $id,
        private string $name,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function changeName(string $newName): void
    {
        $this->name = $newName;
        $this->recordThat(FakeDomainEvent::fromPrimitives($this->id, $this->jsonSerialize()));
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
