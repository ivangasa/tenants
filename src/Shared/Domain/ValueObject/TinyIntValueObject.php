<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\ValueObject;

use Tenants\Shared\Domain\ValueObject\Scalar\IntegerValueObject;

final readonly class TinyIntValueObject extends IntegerValueObject
{
    private function __construct(int $value)
    {
        parent::__construct($value);
    }

    public static function createZero(): self
    {
        return new self(0);
    }

    public static function createOne(): self
    {
        return new self(1);
    }
}
