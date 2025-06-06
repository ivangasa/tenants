<?php

declare(strict_types=1);

namespace Tenants\Shared\Infrastructure\Bus\Command;

use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Tenants\Shared\Domain\Bus\Command\Command;
use Tenants\Shared\Domain\Bus\Command\CommandBus;
use Tenants\Shared\Domain\Bus\Command\UnhandledCommandException;

final class SymfonyMessengerCommandBus implements CommandBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (NoHandlerForMessageException) {
            throw UnhandledCommandException::fromCommand($command);
        }
    }
}
