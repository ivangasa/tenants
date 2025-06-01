<?php

declare(strict_types=1);

namespace Tenants\Tests\Unit\Shared\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tenants\Shared\Domain\Utils;
use Tenants\Shared\Domain\ValueObject\Email;
use Tenants\Shared\Domain\ValueObject\Exception\InvalidEmailException;
use Tenants\Tests\Unit\Shared\Domain\ValueObject\Mother\MotherCreator;

final class EmailTest extends TestCase
{
    #[Test]
    public function should_create_a_random_email(): void
    {
        $email = Email::fromEmail(MotherCreator::random()->email());
        $this->assertNotEmpty($email);
    }

    #[Test]
    public function should_create_an_email_lowered_and_trimmed(): void
    {
        $dirtyEmail = ' iVaN@phpstarter.com ';
        $cleaned = Utils::lowerAndTrim($dirtyEmail);

        $email = Email::fromEmail($dirtyEmail);
        $this->assertNotEmpty($email->value());
        $this->assertSame($email->value(), $cleaned);
        $this->assertSame($cleaned, $email->__toString());
    }

    #[Test]
    public function should_throw_an_exception_when_trying_to_create_an_invalid_email(): void
    {
        $invalidEmail = 'ivanphpstarter.com';

        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage(InvalidEmailException::fromEmail($invalidEmail)->getMessage());

        Email::fromEmail($invalidEmail);
    }
}
