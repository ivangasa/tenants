<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\Bus\Command;

use Tenants\Shared\Domain\Bus\Command\CommandHandler;

final class EmptyCommandHandler implements CommandHandler
{
    private bool $received = false;

    public function __invoke(EmptyCommand $command): void
    {
        $this->received = true;
    }

    public function received(): bool
    {
        return $this->received;
    }
}
