<?php

declare(strict_types=1);

namespace Tenants\Shared\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Tenants\Shared\Domain\ValueObject\NanoId;

abstract class DoctrineNanoIdType extends Type
{
    final public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        // @infection-ignore-all
        $column['fixed'] = true; # Override Doctrine to be able to create a CHAR field
        $column['length'] = NanoId::LENGTH;

        return $platform->getStringTypeDeclarationSQL($column);
    }

    final public function getName(): string
    {
        return $this->name();
    }

    /** @param NanoId $value */
    final public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        return $value->value();
    }

    final public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if (empty($value)) {
            return null;
        }

        $type = $this->type();

        return new $type($value);
    }

    abstract protected function type(): string;

    abstract protected function name(): string;
}
