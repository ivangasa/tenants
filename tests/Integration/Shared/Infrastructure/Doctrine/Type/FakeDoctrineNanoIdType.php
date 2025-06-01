<?php

declare(strict_types=1);

namespace Tenants\Tests\Integration\Shared\Infrastructure\Doctrine\Type;

use Tenants\Shared\Domain\ValueObject\NanoId;
use Tenants\Shared\Infrastructure\Doctrine\Type\DoctrineNanoIdType;

final class FakeDoctrineNanoIdType extends DoctrineNanoIdType
{
    public const string NAME_KEY = 'nano_id_type';

    protected function name(): string
    {
        return self::NAME_KEY;
    }

    protected function type(): string
    {
        return NanoId::class;
    }
}
