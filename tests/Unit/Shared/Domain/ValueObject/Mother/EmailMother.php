<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\ValueObject\Mother;

use Tenants\Shared\Domain\ValueObject\Email;

final class EmailMother
{
    private function __construct() {}

    public static function random(): Email
    {
        return Email::fromEmail(MotherCreator::random()->email());
    }

    public static function fromEmail(string $email): Email
    {
        return Email::fromEmail($email);
    }
}
