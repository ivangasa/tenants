<?php

declare(strict_types=1);

namespace Tenants\Tenancy\Application\Customer\Create;

use Tenants\Shared\Domain\Bus\Command\CommandHandler;

final class CreateCustomerCommandHandler implements CommandHandler
{
    public function __invoke(CreateCustomerCommand $command): void {}
}
