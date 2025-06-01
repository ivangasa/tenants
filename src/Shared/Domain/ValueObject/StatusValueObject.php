<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\ValueObject;

use Tenants\Shared\Domain\ValueObject\Scalar\BooleanValueObject;

final readonly class StatusValueObject extends BooleanValueObject
{
    private function __construct(bool $value)
    {
        parent::__construct($value);
    }

    public static function createEnabled(): self
    {
        return new self(true);
    }

    public static function createDisabled(): self
    {
        return new self(false);
    }
}
