<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\ValueObject;

use Hidehalo\Nanoid\Client as NanoIdClient;
use Tenants\Shared\Domain\ValueObject\Exception\InvalidNanoIdException;
use function strlen;

class NanoId
{
    public const string REGULAR_EXPRESSION_VALID_NANO_ID = '[A-Za-z0-9_-]{12}';
    public const string ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyz_ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const int LENGTH = 12;

    final public function __construct(
        protected readonly string $value
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

    final public static function fromString(string $value): self
    {
        return new static($value);
    }

    final public static function random(): self
    {
        $nanoIdClient = new NanoIdClient();
        $nanoId = $nanoIdClient->formattedId(self::ALPHABET, self::LENGTH);

        return new static($nanoId);
    }

    final public function equals(self $otherNanoId): bool
    {
        return $otherNanoId->value() === $this->value();
    }

    protected function isValid(): void
    {
        if (strlen($this->value()) !== self::LENGTH) {
            throw InvalidNanoIdException::fromInvalidLength($this->value());
        }

        $isValid = preg_match('/^[' . self::ALPHABET . ']+$/', $this->value());

        if (0 === $isValid || false === $isValid) {
            throw InvalidNanoIdException::fromInvalidNanoId($this->value());
        }
    }
}
