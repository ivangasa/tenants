<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\ValueObject\Mother;

use Faker\Factory;
use Faker\Generator;
use Faker\Provider\Base;

final class MotherCreator
{
    private static ?Generator $faker = null;

    public static function random(string $locale = Factory::DEFAULT_LOCALE): Generator
    {
        return self::$faker ??= self::createGenerator($locale, ...self::getProviders());
    }

    /** @param class-string<Base> ...$providers */
    private static function createGenerator(string $locale = Factory::DEFAULT_LOCALE, string ...$providers): Generator
    {
        $generator = Factory::create($locale);
        foreach ($providers as $provider) {
            $generator->addProvider(new $provider($generator));
        }

        return $generator;
    }

    /** @return class-string<Base>[] */
    private static function getProviders(): array
    {
        return [];
    }
}
