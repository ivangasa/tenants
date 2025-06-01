<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tenants\Shared\Domain\ValueObject\TinyIntValueObject;

final class TinyIntValueObjectTest extends TestCase
{
    #[Test]
    public function should_create_tinyint_from_create_zero(): void
    {
        $tinyInt = TinyIntValueObject::createZero();
        $this->assertSame(0, $tinyInt->value());
        $this->assertSame('0', $tinyInt->__toString());
    }

    #[Test]
    public function should_create_tinyint_from_create_one(): void
    {
        $tinyInt = TinyIntValueObject::createOne();
        $this->assertSame(1, $tinyInt->value());
        $this->assertSame('1', $tinyInt->__toString());
    }
}
