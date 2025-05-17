<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\Bus\Command;

use DomainException;
use function sprintf;

class UnhandledCommandException extends DomainException
{
    public const string MESSAGE_KEY = 'Command <%s> has no associated handler';

    public static function fromCommand(Command $command): self
    {
        return new self(sprintf(self::MESSAGE_KEY, $command::class));
    }
}
