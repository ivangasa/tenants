<?php

declare(strict_types=1);

namespace Tenants\Shared\Infrastructure\Bus\Query;

use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Tenants\Shared\Domain\Bus\Query\InvalidQueryHandlerResponseException;
use Tenants\Shared\Domain\Bus\Query\Query;
use Tenants\Shared\Domain\Bus\Query\QueryBus;
use Tenants\Shared\Domain\Bus\Query\QueryResponse;
use Tenants\Shared\Domain\Bus\Query\UnhandledQueryException;

final class InMemorySymfonyQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function ask(Query $query): ?QueryResponse
    {
        try {
            $queryResponse = $this->handle($query);

            if (!$queryResponse instanceof QueryResponse && null !== $queryResponse) {
                throw InvalidQueryHandlerResponseException::fromQuery($queryResponse, $query);
            }

            return $queryResponse;

            // @phpstan-ignore catch.neverThrown
        } catch (NoHandlerForMessageException) {
            throw UnhandledQueryException::fromQuery($query);
        }
    }
}
