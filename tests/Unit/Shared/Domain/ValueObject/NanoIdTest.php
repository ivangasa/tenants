<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tenants\Shared\Domain\ValueObject\Exception\InvalidNanoIdException;
use Tenants\Tests\Unit\Shared\Domain\ValueObject\Mother\NanoIdMother;

final class NanoIdTest extends TestCase
{
    #[Test]
    public function should_create_a_random_nano_id(): void
    {
        $validNanoIdString = NanoIdMother::random();
        $nanoId = NanoIdMother::fromString($validNanoIdString->value());

        $this->assertSame($validNanoIdString->value(), $nanoId->value());
        $this->assertSame($validNanoIdString->value(), $nanoId->__toString());
        $this->assertSame($validNanoIdString->value(), (string) $nanoId);
    }

    #[Test]
    public function should_throw_an_exception_for_invalid_length_nano_id(): void
    {
        $nanoId = 'invalidLength';

        $this->expectException(InvalidNanoIdException::class);
        $this->expectExceptionMessage(InvalidNanoIdException::fromInvalidLength($nanoId)->getMessage());

        NanoIdMother::fromString($nanoId);
    }

    #[Test]
    public function should_throw_an_exception_for_invalid_alphabet_nano_id(): void
    {
        $nanoId = '12345678901234%';

        $this->expectException(InvalidNanoIdException::class);
        $this->expectExceptionMessage(InvalidNanoIdException::fromInvalidNanoId($nanoId)->getMessage());

        NanoIdMother::fromString($nanoId);
    }

    #[Test]
    public function should_return_true_when_comparing_two_nano_ids_with_same_value(): void
    {
        $randomNanoIdValue = NanoIdMother::random();

        $oneNanoId = NanoIdMother::fromString($randomNanoIdValue->value());
        $anotherNanoId = NanoIdMother::fromString($randomNanoIdValue->value());

        $this->assertTrue($oneNanoId->equals($anotherNanoId));
    }

    #[Test]
    public function should_return_false_when_comparing_two_nano_ids_with_same_value(): void
    {
        $oneNanoId = NanoIdMother::random();
        $anotherNanoId = NanoIdMother::random();

        $this->assertFalse($oneNanoId->equals($anotherNanoId));
    }
}
