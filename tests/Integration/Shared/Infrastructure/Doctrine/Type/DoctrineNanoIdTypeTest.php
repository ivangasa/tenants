<?php

declare(strict_types=1);

namespace Tenants\Tests\Integration\Shared\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tenants\Shared\Domain\ValueObject\NanoId;
use Tenants\Shared\Infrastructure\Doctrine\Type\DoctrineNanoIdType;

final class DoctrineNanoIdTypeTest extends TestCase
{
    private DoctrineNanoIdType $nanoIdType;
    private AbstractPlatform $abstractPlatform;

    #[Test]
    public function should_convert_from_nano_id_to_database_value(): void
    {
        $nanoId = NanoId::random();

        $convertedNanoIdToDatabaseValue = $this->nanoIdType->convertToDatabaseValue(
            $nanoId,
            $this->abstractPlatform
        );

        $this->assertSame($nanoId->value(), $convertedNanoIdToDatabaseValue);
    }

    #[Test]
    public function should_convert_from_database_value_to_nano_id(): void
    {
        $nanoId = NanoId::random();

        $convertedToNanoId = $this->nanoIdType->convertToPHPValue($nanoId->value(), $this->abstractPlatform);

        $this->assertInstanceOf(NanoId::class, $convertedToNanoId);
        $this->assertSame($convertedToNanoId->value(), $nanoId->value());
        $this->assertSame(FakeDoctrineNanoIdType::NAME_KEY, $this->nanoIdType->getName());
    }

    #[Test]
    public function should_return_null_when_converting_empty_database_value_to_php(): void
    {
        $convertedToNanoId = $this->nanoIdType->convertToPHPValue('', $this->abstractPlatform);
        $this->assertNull($convertedToNanoId);
    }

    #[Test]
    public function should_return_correct_sql_declaration(): void
    {
        $column = [
            'name' => 'nano_id',
            'length' => NanoId::LENGTH,
        ];
        $sqlDeclaration = $this->nanoIdType->getSQLDeclaration($column, $this->abstractPlatform);

        $this->assertStringContainsString('CHAR', $sqlDeclaration);
        $this->assertStringContainsString((string) NanoId::LENGTH, $sqlDeclaration);
    }

    protected function setUp(): void
    {
        $this->nanoIdType = new FakeDoctrineNanoIdType();
        $this->abstractPlatform = new PostgreSQLPlatform();
    }
}
