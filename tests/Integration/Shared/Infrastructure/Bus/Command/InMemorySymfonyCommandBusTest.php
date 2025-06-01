<?php

declare(strict_types=1);

namespace Tenants\Tests\Integration\Shared\Infrastructure\Bus\Command;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Tenants\Shared\Domain\Bus\Command\NoHandlerForCommandException;
use Tenants\Shared\Infrastructure\Bus\Command\InMemorySymfonyCommandBus;
use Tenants\Tests\Unit\Shared\Domain\Bus\Command\EmptyCommand;
use Tenants\Tests\Unit\Shared\Domain\Bus\Command\EmptyCommandHandler;
use Tenants\Tests\Unit\Shared\Domain\Bus\Command\FailingCommandHandler;
use Tenants\Tests\Unit\Shared\Domain\Bus\Command\FakeCommand;
use Tenants\Tests\Unit\Shared\Domain\Bus\Command\FakeCommandHandler;
use Tenants\Tests\Unit\Shared\Domain\ValueObject\Mother\MotherCreator;
use Tenants\Tests\Unit\Shared\Domain\ValueObject\Mother\NanoIdMother;
use Throwable;

final class InMemorySymfonyCommandBusTest extends TestCase
{
    /**
     * @throws Throwable
     * @throws ExceptionInterface
     */
    #[Test]
    public function should_dispatch_a_command_to_its_handler(): void
    {
        $command = new EmptyCommand();
        $commandHandler = new EmptyCommandHandler();

        $handlersLocator = new HandlersLocator([
            EmptyCommand::class => [$commandHandler],
        ]);

        $messageBus = new MessageBus([new HandleMessageMiddleware($handlersLocator)]);
        $commandBus = new InMemorySymfonyCommandBus($messageBus);

        $commandBus->dispatch($command);
        $this->assertTrue($commandHandler->received());
    }

    /**
     * @throws Throwable
     * @throws ExceptionInterface
     */
    #[Test]
    public function should_dispatch_multiple_commands_to_its_respective_handlers(): void
    {
        $firstCommand = new EmptyCommand();
        $secondCommand = new FakeCommand(NanoIdMother::random()->value(), 'Ivan');

        $firstCommandHandler = new EmptyCommandHandler();
        $secondCommandHandler = new FakeCommandHandler();

        $handlersLocator = new HandlersLocator([
            EmptyCommand::class => [$firstCommandHandler],
            FakeCommand::class => [$secondCommandHandler],
        ]);

        $messageBus = new MessageBus([new HandleMessageMiddleware($handlersLocator)]);
        $commandBus = new InMemorySymfonyCommandBus($messageBus);

        $commandBus->dispatch($firstCommand);
        $commandBus->dispatch($secondCommand);

        $this->assertTrue($firstCommandHandler->received());
        $this->assertTrue($secondCommandHandler->received());
    }

    /**
     * @throws Throwable
     * @throws ExceptionInterface
     */
    #[Test]
    public function should_dispatch_command_with_params_to_its_handler(): void
    {
        $command = new FakeCommand(NanoIdMother::random()->value(), MotherCreator::random()->name());
        $commandHandler = new FakeCommandHandler();

        $handlersLocator = new HandlersLocator([
            FakeCommand::class => [$commandHandler],
        ]);

        $messageBus = new MessageBus([new HandleMessageMiddleware($handlersLocator)]);
        $commandBus = new InMemorySymfonyCommandBus($messageBus);

        $commandBus->dispatch($command);

        $this->assertTrue($commandHandler->received());
        $this->assertSame($command->id(), $commandHandler->id());
        $this->assertSame($command->name(), $commandHandler->name());
    }

    /**
     * @throws Throwable
     * @throws ExceptionInterface
     */
    #[Test]
    public function should_throw_exception_when_command_has_no_handler(): void
    {
        $this->expectException(NoHandlerForCommandException::class);

        $command = new EmptyCommand();
        $handlersLocator = new HandlersLocator([]);
        $messageBus = new MessageBus([new HandleMessageMiddleware($handlersLocator)]);
        $commandBus = new InMemorySymfonyCommandBus($messageBus);

        $commandBus->dispatch($command);
    }

    #[Test]
    public function should_throw_original_exception_from_handler_failed_exception(): void
    {
        $command = new EmptyCommand();
        $failingHandler = new FailingCommandHandler();

        $handlersLocator = new HandlersLocator([
            EmptyCommand::class => [$failingHandler],
        ]);

        $messageBus = new MessageBus([new HandleMessageMiddleware($handlersLocator)]);

        $commandBus = new InMemorySymfonyCommandBus($messageBus);

        try {
            $commandBus->dispatch($command);
            $this->fail('Expected RuntimeException was not thrown.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Something went wrong!', $exception->getMessage());
        } catch (Throwable $unexpected) {
            $this->fail('Expected RuntimeException but got ' . get_class($unexpected));
        }
    }
}
