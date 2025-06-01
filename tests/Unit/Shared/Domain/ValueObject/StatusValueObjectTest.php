<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tenants\Shared\Domain\ValueObject\StatusValueObject;

final class StatusValueObjectTest extends TestCase
{
    #[Test]
    public function should_create_enabled_status_with_true_boolean_value(): void
    {
        $enabledStatus = StatusValueObject::createEnabled();

        $this->assertTrue($enabledStatus->value());
        $this->assertSame((string) $enabledStatus->value(), $enabledStatus->__toString());
    }

    #[Test]
    public function should_create_disabled_status_with_false_boolean_value(): void
    {
        $disabledStatus = StatusValueObject::createDisabled();

        $this->assertFalse($disabledStatus->value());
        $this->assertSame((string) $disabledStatus->value(), $disabledStatus->__toString());
    }
}
