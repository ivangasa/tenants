<?php

declare(strict_types=1);

namespace Tenants\Shared\Ui\Http;

use Tenants\Shared\Domain\Bus\Command\Command;
use Tenants\Shared\Domain\Bus\Command\CommandBus;

abstract readonly class SharedController
{
    public function __construct(
        private CommandBus $commandBus,
    ) {}

    protected function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
