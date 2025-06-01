<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\ValueObject\Scalar;

interface ValueObject
{
    public function value(): mixed;
}
