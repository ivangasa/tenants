<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\Bus\Command;

use DomainException;

use function sprintf;

final class NoHandlerForCommandException extends DomainException
{
    protected const string NO_COMMAND_HANDLER_FOR_COMMAND_MESSAGE = 'Command <%s> has no associated handler';

    public static function fromCommand(Command $command): self
    {
        return new self(sprintf(self::NO_COMMAND_HANDLER_FOR_COMMAND_MESSAGE, $command::class));
    }
}
