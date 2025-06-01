<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\ValueObject\Exception;

use DomainException;

use function sprintf;

final class InvalidDateTimeException extends DomainException
{
    protected const string INVALID_DATETIME_MESSAGE_KEY = 'Date <%s> is not a valid date time';
    protected const string INVALID_FORMAT_MESSAGE_KEY = 'Date <%s> with format <%s> is not a valid date';

    public static function fromString(string $dateTime): self
    {
        return new self(sprintf(self::INVALID_DATETIME_MESSAGE_KEY, $dateTime));
    }

    public static function fromStringWithFormat(string $dateTime, string $format): self
    {
        return new self(sprintf(self::INVALID_FORMAT_MESSAGE_KEY, $dateTime, $format));
    }
}
