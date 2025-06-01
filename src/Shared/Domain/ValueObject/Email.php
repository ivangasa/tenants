<?php

declare(strict_types=1);

namespace Tenants\Shared\Domain\ValueObject;

use Override;
use Tenants\Shared\Domain\Utils;
use Tenants\Shared\Domain\ValueObject\Exception\InvalidEmailException;
use Tenants\Shared\Domain\ValueObject\Scalar\StringValueObject;

use const FILTER_VALIDATE_EMAIL;

final readonly class Email extends StringValueObject
{
    private function __construct(string $email)
    {
        parent::__construct(Utils::lowerAndTrim($email));
        $this->isValid();
    }

    public static function fromEmail(string $email): self
    {
        return new self($email);
    }

    #[Override]
    protected function isValid(): void
    {
        if (!filter_var($this->value(), FILTER_VALIDATE_EMAIL)) {
            throw InvalidEmailException::fromEmail($this->value());
        }
    }
}
