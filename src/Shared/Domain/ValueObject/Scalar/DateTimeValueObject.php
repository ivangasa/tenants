<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\ValueObject\Scalar;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Override;
use Tenants\Shared\Domain\ValueObject\Exception\InvalidDateTimeException;

/** @phpstan-consistent-constructor */
final class DateTimeValueObject extends DateTimeImmutable implements ValueObject
{
    public const string FORMAT = DateTimeInterface::ATOM;

    private readonly DateTimeInterface $dateTime;

    private function __construct(DateTimeInterface $value)
    {
        $this->dateTime = $value;
        parent::__construct($value->format(self::FORMAT));
    }

    public static function fromNow(): self
    {
        $dateTime = new DateTimeImmutable();

        return new self($dateTime);
    }

    public static function fromDateTime(DateTimeInterface $dateTime): self
    {
        $dateTime = DateTimeImmutable::createFromInterface($dateTime);

        return new self($dateTime);
    }

    /** @throws Exception */
    public static function fromString(string $stringDateTime): self
    {
        $dateTime = DateTimeImmutable::createFromFormat(self::FORMAT, $stringDateTime);
        if (!$dateTime instanceof DateTimeInterface) {
            throw InvalidDateTimeException::fromString($stringDateTime);
        }

        return new self($dateTime);
    }

    /** @throws Exception */
    public static function fromStringWithFormat(string $stringDateTime, string $format = self::FORMAT): static
    {
        $dateTime = DateTimeImmutable::createFromFormat($format, $stringDateTime);
        if (!$dateTime instanceof DateTimeInterface) {
            throw InvalidDateTimeException::fromStringWithFormat($stringDateTime, $format);
        }

        return new self($dateTime);
    }

    #[Override]
    public function value(): DateTimeInterface
    {
        return $this->dateTime;
    }
}
