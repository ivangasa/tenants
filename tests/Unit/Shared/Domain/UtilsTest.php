<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use JsonException;
use JsonSerializable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tenants\Shared\Domain\Utils;

final class UtilsTest extends TestCase
{
    #[Test]
    public function should_return_convert_random_datetime_to_string(): void
    {
        $dateTime = new DateTimeImmutable();
        $dateTimeString = Utils::dateTimeToString($dateTime);

        $this->assertNotEmpty($dateTimeString);
        $this->assertSame($dateTime->format(DateTimeInterface::ATOM), $dateTimeString);
    }

    #[Test]
    public function should_return_a_given_datetime_to_string(): void
    {
        $date = new DateTimeImmutable('2024-01-01 12:00:00');
        $formatted = Utils::dateTimeToString($date, 'Y-m-d H:i:s');

        $this->assertSame('2024-01-01 12:00:00', $formatted);
    }

    #[Test]
    public function should_convert_snake_case_to_camel_case(): void
    {
        $this->assertSame('helloWorld', Utils::snakeToCamelCase('hello_world'));
        $this->assertSame('multipleWordsTogether', Utils::snakeToCamelCase('multiple_words_together'));
        $this->assertSame('alreadyCamel', Utils::snakeToCamelCase('alreadyCamel'));
        $this->assertSame('aVariable', Utils::snakeToCamelCase('A_variable'));
        $this->assertSame('myVariable', Utils::snakeToCamelCase('my_variable'));
        $this->assertSame('a', Utils::snakeToCamelCase('a'));
        $this->assertSame('', Utils::snakeToCamelCase(''));
    }

    #[Test]
    public function should_lowercase_and_trim_string(): void
    {
        $this->assertSame('hello', Utils::lowerAndTrim(' HeLLo '));
        $this->assertSame('world', Utils::lowerAndTrim('WORLD'));
        $this->assertSame('', Utils::lowerAndTrim('   '));
    }

    #[Test]
    public function should_convert_object_to_array(): void
    {
        $object = (object) [
            'name' => 'Iván',
            'age' => 37,
        ];
        $array = Utils::objectToArray($object);

        $this->assertSame([
            'name' => 'Iván',
            'age' => 37,
        ], $array);
    }

    #[Test]
    public function should_return_an_empty_array_if_not_decoded_json(): void
    {
        $object = new class() implements JsonSerializable {
            public function jsonSerialize(): string
            {
                return 'simple string';
            }
        };

        $result = Utils::objectToArray($object);

        $this->assertSame([], $result);
    }

    #[Test]
    public function should_throw_an_exception_when_json_could_not_be_serialized(): void
    {
        $this->expectException(JsonException::class);

        Utils::objectToArray((object) [
            'foo' => tmpfile(),
        ]);
    }
}
