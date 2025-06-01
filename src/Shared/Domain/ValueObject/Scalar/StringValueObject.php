<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\ValueObject\Scalar;

use Override;

abstract readonly class StringValueObject implements ValueObject
{
    public function __construct(
        protected string $value,
    ) {}

    #[Override]
    public function __toString(): string
    {
        return $this->value;
    }

    #[Override]
    final public function value(): string
    {
        return $this->value;
    }

    abstract protected function isValid(): void;
}
