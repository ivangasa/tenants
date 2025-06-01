<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\ValueObject;

use Hidehalo\Nanoid\Client as NanoIdClient;
use Tenants\Shared\Domain\ValueObject\Exception\InvalidNanoIdException;

use function strlen;

final class NanoId
{
    public const string REGULAR_EXPRESSION_VALID_NANO_ID = '[A-Za-z0-9_-]{12}';
    public const string ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyz_ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const int LENGTH = 15;

    public function __construct(
        private readonly string $value,
    ) {
        $this->isValid();
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function random(): self
    {
        $nanoIdClient = new NanoIdClient();
        $nanoId = $nanoIdClient->formattedId(self::ALPHABET, self::LENGTH);

        return new self($nanoId);
    }

    public function equals(self $otherNanoId): bool
    {
        return $otherNanoId->value() === $this->value();
    }

    private function isValid(): void
    {
        if (self::LENGTH !== strlen($this->value())) {
            throw InvalidNanoIdException::fromInvalidLength($this->value());
        }

        if (self::LENGTH !== strspn($this->value(), self::ALPHABET)) {
            throw InvalidNanoIdException::fromInvalidNanoId($this->value());
        }
    }
}
