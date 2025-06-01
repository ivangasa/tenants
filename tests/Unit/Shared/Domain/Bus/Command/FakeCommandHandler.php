<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\Bus\Command;

use Tenants\Shared\Domain\Bus\Command\CommandHandler;

final class FakeCommandHandler implements CommandHandler
{
    private string $id = '';
    private string $name = '';
    private bool $received = false;

    public function __invoke(FakeCommand $command): void
    {
        $this->id = $command->id();
        $this->name = $command->name();
        $this->received = true;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function received(): bool
    {
        return $this->received;
    }
}
