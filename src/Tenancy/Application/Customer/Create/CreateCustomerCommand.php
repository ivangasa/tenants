<?php

declare(strict_types=1);

namespace Tenants\Tenancy\Application\Customer\Create;

use Tenants\Shared\Domain\Bus\Command\Command;

final readonly class CreateCustomerCommand implements Command
{
    public const string COMMAND_NAME = 'tenants.tenancy.customer.create';

    public function __construct(
        private readonly string $name,
    ) {}

    public function name(): string
    {
        return $this->name;
    }

    public function commandName(): string
    {
        return self::COMMAND_NAME;
    }
}
