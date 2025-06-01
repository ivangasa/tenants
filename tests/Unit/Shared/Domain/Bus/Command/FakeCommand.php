<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\Bus\Command;

use Tenants\Shared\Domain\Bus\Command\Command;

final class FakeCommand implements Command
{
    private const string COMMAND_NAME = 'fake.command';

    public function __construct(
        private readonly string $id,
        private readonly string $name,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function commandName(): string
    {
        return self::COMMAND_NAME;
    }
}
