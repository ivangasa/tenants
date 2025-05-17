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

final class ParameterizedCommandTest extends TestCase
{
    #[Test]
    public function should_dispatch_command_with_parameters_to_its_handler(): void
    {
        // Arrange
        $id = 'test-id-123';
        $name = 'Test Name';
        $command = new CreateUserCommand($id, $name);

        $handler = new CreateUserCommandHandler();

        $handlersLocator = new HandlersLocator([
            CreateUserCommand::class => [$handler],
        ]);

        $messageBus = new MessageBus([new HandleMessageMiddleware($handlersLocator)]);

        $commandBus = new SymfonyMessengerCommandBus($messageBus);

        // Act
        $commandBus->dispatch($command);

        // Assert
        $this->assertTrue($handler->hasBeenCalled());
        $this->assertEquals($id, $handler->getReceivedId());
        $this->assertEquals($name, $handler->getReceivedName());
    }
}

final class CreateUserCommand implements Command
{
    public function __construct(
        private readonly string $id,
        private readonly string $name
    ) {
    }

    public function commandName(): string
    {
        return 'create_user';
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}

final class CreateUserCommandHandler implements CommandHandler
{
    private bool $called = false;
    private string $receivedId = '';
    private string $receivedName = '';

    public function __invoke(CreateUserCommand $command): void
    {
        $this->called = true;
        $this->receivedId = $command->id();
        $this->receivedName = $command->name();
    }

    public function hasBeenCalled(): bool
    {
        return $this->called;
    }

    public function getReceivedId(): string
    {
        return $this->receivedId;
    }

    public function getReceivedName(): string
    {
        return $this->receivedName;
    }
}
