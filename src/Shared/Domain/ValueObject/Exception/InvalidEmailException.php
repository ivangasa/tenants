<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\ValueObject\Exception;

use DomainException;

use function sprintf;

final class InvalidEmailException extends DomainException
{
    protected const string MESSAGE_KEY = 'Email <%s> is not a valid email address.';

    public static function fromEmail(string $email): self
    {
        return new self(sprintf(self::MESSAGE_KEY, $email));
    }
}
