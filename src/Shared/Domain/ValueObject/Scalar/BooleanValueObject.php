<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\ValueObject\Scalar;

use Override;

abstract readonly class BooleanValueObject implements ValueObject
{
    public function __construct(
        private bool $value,
    ) {}

    #[Override]
    public function __toString(): string
    {
        return (string) $this->value;
    }

    #[Override]
    final public function value(): bool
    {
        return $this->value;
    }
}
