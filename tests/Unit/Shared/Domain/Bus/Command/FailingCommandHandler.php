<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\Bus\Command;

use RuntimeException;

final class FailingCommandHandler
{
    public function __invoke(EmptyCommand $command): void
    {
        throw new RuntimeException('Something went wrong!');
    }
}
