<?php

declare(strict_types=1);

namespace Tenants\Tests\Integration\Shared\Infrastructure\Bus\Query;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Tenants\Shared\Domain\Bus\Query\InvalidQueryHandlerResponseException;
use Tenants\Shared\Domain\Bus\Query\QueryResponse;
use Tenants\Shared\Domain\Bus\Query\UnhandledQueryException;
use Tenants\Shared\Infrastructure\Bus\Query\InMemorySymfonyQueryBus;
use Tenants\Tests\Unit\Shared\Domain\Bus\Query\EmptyQuery;
use Tenants\Tests\Unit\Shared\Domain\Bus\Query\EmptyQueryHandler;
use Tenants\Tests\Unit\Shared\Domain\Bus\Query\InvalidQueryHandler;
use Tenants\Tests\Unit\Shared\Domain\Bus\Query\NullQueryHandler;

final class InMemorySymfonyQueryBusTest extends TestCase
{
    #[Test]
    public function should_ask_a_query_and_receive_its_response(): void
    {
        $query = new EmptyQuery();
        $queryHandler = new EmptyQueryHandler();

        $handlersLocator = new HandlersLocator([
            EmptyQuery::class => [$queryHandler],
        ]);

        $messageBus = new MessageBus([new HandleMessageMiddleware($handlersLocator)]);
        $queryBus = new InMemorySymfonyQueryBus($messageBus);
        $queryResponse = $queryBus->ask($query);

        $this->assertInstanceOf(QueryResponse::class, $queryResponse);
    }


    #[Test]
    public function should_return_null_when_handler_returns_null(): void
    {
        $query = new EmptyQuery();
        $nullQueryHandler = new NullQueryHandler();

        $handlersLocator = new HandlersLocator([
            EmptyQuery::class => [$nullQueryHandler],
        ]);

        $messageBus = new MessageBus([new HandleMessageMiddleware($handlersLocator)]);
        $queryBus = new InMemorySymfonyQueryBus($messageBus);

        $this->assertNull($queryBus->ask($query));
    }

    #[Test]
    public function should_throw_exception_when_handler_returns_invalid_type(): void
    {
        $this->expectException(InvalidQueryHandlerResponseException::class);

        $query = new EmptyQuery();
        $invalidQueryHandler = new InvalidQueryHandler();

        $handlersLocator = new HandlersLocator([
            EmptyQuery::class => [$invalidQueryHandler],
        ]);

        $messageBus = new MessageBus([new HandleMessageMiddleware($handlersLocator)]);
        $queryBus = new InMemorySymfonyQueryBus($messageBus);

        $queryBus->ask($query);
    }

    #[Test]
    public function should_throw_unhandled_query_exception_when_no_handler_found(): void
    {
        $this->expectException(UnhandledQueryException::class);

        $query = new EmptyQuery();
        $handlersLocator = new HandlersLocator([]);

        $messageBus = new MessageBus([new HandleMessageMiddleware($handlersLocator)]);
        $queryBus = new InMemorySymfonyQueryBus($messageBus);

        $queryBus->ask($query);
    }
}
