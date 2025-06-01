<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\Bus\Command;

use Tenants\Shared\Domain\Bus\Command\Command;

final class EmptyCommand implements Command
{
    private const string COMMAND_NAME = 'empty.command';

    public function commandName(): string
    {
        return self::COMMAND_NAME;
    }
}
