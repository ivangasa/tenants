<?php

declare(strict_types=1);

namespace Tenants\Shared\Infrastructure\Symfony\Exception;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

final readonly class ExceptionToHttpStatusEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ExceptionResponseFactory $exceptionResponseFactory,
        private ExceptionToHttpStatusMapper $exceptionToHttpStatusMapper,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $exceptionEvent): void
    {
        $throwable = $exceptionEvent->getThrowable();

        if ($throwable instanceof HandlerFailedException) {
            $previous = $throwable->getPrevious();
            if ($previous instanceof Throwable) {
                $throwable = $previous;
            }
        }

        $statusCode = $this->exceptionToHttpStatusMapper->statusCodeFor($throwable::class);
        $jsonResponse = $this->exceptionResponseFactory->createExceptionResponse($throwable, $statusCode);

        $exceptionEvent->setResponse($jsonResponse);
    }
}
