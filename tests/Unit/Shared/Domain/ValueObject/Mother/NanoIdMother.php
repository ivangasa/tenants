<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\ValueObject\Mother;

use Tenants\Shared\Domain\ValueObject\NanoId;

final class NanoIdMother
{
    public static function random(): NanoId
    {
        return NanoId::fromString(NanoId::random()->value());
    }

    public static function fromString(string $value): NanoId
    {
        return NanoId::fromString($value);
    }
}
