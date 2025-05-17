<?php

declare(strict_types=1);

namespace Tenants\Tests\Integration\Shared\Infrastructure\Bus\Command;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Tenants\Shared\Domain\Bus\Command\Command;
use Tenants\Shared\Domain\Bus\Command\CommandHandler;
use Tenants\Shared\Infrastructure\Bus\Command\SymfonyMessengerCommandBus;

final class MultipleCommandsTest extends TestCase
{
    #[Test]
    public function should_dispatch_multiple_commands_to_their_respective_handlers(): void
    {
        // Arrange
        $firstCommand = new FirstCommand();
        $secondCommand = new SecondCommand();

        $firstHandler = new FirstCommandHandler();
        $secondHandler = new SecondCommandHandler();

        $handlersLocator = new HandlersLocator([
            FirstCommand::class => [$firstHandler],
            SecondCommand::class => [$secondHandler],
        ]);

        $messageBus = new MessageBus([new HandleMessageMiddleware($handlersLocator)]);

        $commandBus = new SymfonyMessengerCommandBus($messageBus);

        // Act
        $commandBus->dispatch($firstCommand);
        $commandBus->dispatch($secondCommand);

        // Assert
        $this->assertTrue($firstHandler->hasBeenCalled());
        $this->assertTrue($secondHandler->hasBeenCalled());
        $this->assertEquals('first.command', $firstHandler->getReceivedCommandName());
        $this->assertEquals('second.command', $secondHandler->getReceivedCommandName());
    }
}

final class FirstCommand implements Command
{
    public function commandName(): string
    {
        return 'first.command';
    }
}

final class SecondCommand implements Command
{
    public function commandName(): string
    {
        return 'second.command';
    }
}

final class FirstCommandHandler implements CommandHandler
{
    private bool $called = false;
    private string $receivedCommandName = '';

    public function __invoke(FirstCommand $command): void
    {
        $this->called = true;
        $this->receivedCommandName = $command->commandName();
    }

    public function hasBeenCalled(): bool
    {
        return $this->called;
    }

    public function getReceivedCommandName(): string
    {
        return $this->receivedCommandName;
    }
}

final class SecondCommandHandler implements CommandHandler
{
    private bool $called = false;
    private string $receivedCommandName = '';

    public function __invoke(SecondCommand $command): void
    {
        $this->called = true;
        $this->receivedCommandName = $command->commandName();
    }

    public function hasBeenCalled(): bool
    {
        return $this->called;
    }

    public function getReceivedCommandName(): string
    {
        return $this->receivedCommandName;
    }
}
