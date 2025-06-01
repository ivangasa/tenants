<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use JsonException;

use function is_array;

use const JSON_THROW_ON_ERROR;

final class Utils
{
    public const string DATE_FORMAT = DateTimeInterface::ATOM;

    public static function dateTimeToString(
        ?DateTimeInterface $dateTime = null,
        string $format = self::DATE_FORMAT,
    ): string {
        $dateTime ??= new DateTimeImmutable();

        return $dateTime->format($format);
    }

    public static function snakeToCamelCase(string $input): string
    {
        $camelCased = preg_replace_callback(
            '/_([a-zA-Z])/',
            static fn ($matches): string => strtoupper($matches[1]),
            $input
        );

        return lcfirst($camelCased ?? '');
    }

    public static function lowerAndTrim(string $value): string
    {
        return strtolower(trim($value));
    }

    /** @return array<mixed, mixed> */
    public static function objectToArray(object $data): array
    {
        try {
            $jsonEncoded = json_encode($data, JSON_THROW_ON_ERROR);
            $decodedArray = json_decode($jsonEncoded, true, JSON_THROW_ON_ERROR);

            if (!is_array($decodedArray)) {
                return [];
            }

            return $decodedArray;
        } catch (JsonException $e) {
            throw new JsonException($e->getMessage(), $e->getCode());
        }
    }
}
