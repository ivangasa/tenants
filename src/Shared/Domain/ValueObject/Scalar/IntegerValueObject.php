<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\ValueObject\Scalar;

use Override;

abstract readonly class IntegerValueObject implements ValueObject
{
    public function __construct(
        protected int $value,
    ) {}

    #[Override]
    public function __toString(): string
    {
        return (string) $this->value;
    }

    #[Override]
    final public function value(): int
    {
        return $this->value;
    }
}
