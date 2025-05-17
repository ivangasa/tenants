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
use Tenants\Shared\Domain\Bus\Command\UnhandledCommandException;
use Tenants\Shared\Infrastructure\Bus\Command\SymfonyMessengerCommandBus;

final class SymfonyMessengerCommandBusTest extends TestCase
{
    #[Test]
    public function should_dispatch_command_to_its_handler(): void
    {
        // Arrange
        $command = new TestCommand();
        $handler = new TestCommandHandler();

        $handlersLocator = new HandlersLocator([
            TestCommand::class => [$handler],
        ]);

        $messageBus = new MessageBus([new HandleMessageMiddleware($handlersLocator)]);

        $commandBus = new SymfonyMessengerCommandBus($messageBus);

        // Act
        $commandBus->dispatch($command);

        // Assert
        $this->assertTrue($handler->hasBeenCalled());
    }

    #[Test]
    public function should_throw_an_exception_when_command_has_no_handler(): void
    {
        // Arrange
        $command = new TestCommand();

        $handlersLocator = new HandlersLocator([]);

        $messageBus = new MessageBus([new HandleMessageMiddleware($handlersLocator)]);

        $commandBus = new SymfonyMessengerCommandBus($messageBus);

        // Assert
        $this->expectException(UnhandledCommandException::class);

        // Act
        $commandBus->dispatch($command);
    }
}

final class TestCommand implements Command
{
    public function commandName(): string
    {
        return 'test.command';
    }
}

final class TestCommandHandler implements CommandHandler
{
    private bool $called = false;

    public function __invoke(TestCommand $command): void
    {
        $this->called = true;
    }

    public function hasBeenCalled(): bool
    {
        return $this->called;
    }
}
