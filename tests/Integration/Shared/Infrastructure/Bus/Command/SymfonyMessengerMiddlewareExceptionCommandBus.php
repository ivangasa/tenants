<?php

declare(strict_types=1);

namespace Tenants\Tests\Integration\Shared\Infrastructure\Bus\Command;

use Exception;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Middleware\StackMiddleware;

final class SymfonyMessengerMiddlewareExceptionCommandBus implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface|StackMiddleware $stack): Envelope
    {
        throw new class('Simulated middleware failure') extends Exception implements ExceptionInterface {};
    }
}
