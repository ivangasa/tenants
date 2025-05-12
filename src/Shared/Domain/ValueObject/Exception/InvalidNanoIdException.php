<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\ValueObject\Exception;

use DomainException;

use function sprintf;

final class InvalidNanoIdException extends DomainException
{
    public const string INVALID_LENGTH_MESSAGE_KEY = 'NanoId with value <%s> has an invalid length';
    public const string INVALID_ALPHABET_MESSAGE_KEY = 'NanoId with value <%s> has an invalid alphabet';

    public static function fromInvalidLength(string $nanoId): self
    {
        return new self(sprintf(
            self::INVALID_LENGTH_MESSAGE_KEY,
            $nanoId
        ));
    }

    public static function fromInvalidNanoId(string $nanoId): self
    {
        return new self(sprintf(
            self::INVALID_ALPHABET_MESSAGE_KEY,
            $nanoId
        ));
    }
}
