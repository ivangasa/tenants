<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\ValueObject;

use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tenants\Shared\Domain\ValueObject\Exception\InvalidDateTimeException;
use Tenants\Shared\Domain\ValueObject\Scalar\DateTimeValueObject;

final class DateTimeValueObjectTest extends TestCase
{
    /** @throws Exception */
    #[Test]
    public function should_create_a_datetime_value_object(): void
    {
        $dateTime = new DateTimeImmutable();
        $dateTimeValueObject = DateTimeValueObject::fromDateTime($dateTime);

        $this->assertSame(
            $dateTime->format(DateTimeValueObject::FORMAT),
            $dateTimeValueObject->value()
                ->format(DateTimeValueObject::FORMAT)
        );
    }

    #[Test]
    public function should_create_a_datetime_value_object_without_given_value(): void
    {
        $datetime = DateTimeValueObject::fromNow();
        $this->assertNotEmpty($datetime->format(DateTimeValueObject::FORMAT));
    }

    /** @throws Exception */
    #[Test]
    public function should_create_datetime_value_object_from_valid_string(): void
    {
        $nowDatetime = new DateTimeImmutable();
        $formatted = $nowDatetime->format(DateTimeValueObject::FORMAT);
        $dateTime = DateTimeValueObject::fromString($formatted);

        $this->assertSame($formatted, $dateTime->value()->format(DateTimeValueObject::FORMAT));
    }

    /** @throws Exception */
    #[Test]
    public function should_throw_exception_with_invalid_string_format(): void
    {
        $invalidDateTime = 'not-a-valid-datetime';

        $this->expectException(InvalidDateTimeException::class);
        $this->expectExceptionMessage(InvalidDateTimeException::fromString($invalidDateTime)->getMessage());

        DateTimeValueObject::fromString($invalidDateTime);
    }

    /** @throws Exception */
    #[Test]
    public function should_create_datetime_value_object_from_string_with_custom_format(): void
    {
        $nowDateTime = new DateTimeImmutable();
        $customFormat = 'Y-m-d H:i:s';
        $formatted = $nowDateTime->format($customFormat);
        $dateTime = DateTimeValueObject::fromStringWithFormat($formatted, $customFormat);

        $this->assertSame($formatted, $dateTime->value()->format($customFormat));
    }

    /** @throws Exception */
    #[Test]
    public function should_throw_exception_with_invalid_string_and_custom_format(): void
    {
        $invalidDateTime = 'not-a-date';
        $customFormat = 'Y-m-d H:i:s';

        $this->expectException(InvalidDateTimeException::class);
        $this->expectExceptionMessage(
            InvalidDateTimeException::fromStringWithFormat($invalidDateTime, $customFormat)->getMessage()
        );

        DateTimeValueObject::fromStringWithFormat($invalidDateTime, $customFormat);
    }
}
