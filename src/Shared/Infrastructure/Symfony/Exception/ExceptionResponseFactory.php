<?php

declare(strict_types=1);

namespace Tenants\Shared\Infrastructure\Symfony\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

final readonly class ExceptionResponseFactory
{
    public function __construct(
        private ExceptionToHttpStatusMapper $exceptionToHttpStatusMapper,
    ) {}

    public function createExceptionResponse(Throwable $throwable, int $statusCode): JsonResponse
    {
        $mappedStatusCode = $this->exceptionToHttpStatusMapper->toMap($throwable);
        $errors = [];

        $previous = $throwable->getPrevious();
        if ($previous instanceof ValidationFailedException) {
            foreach ($previous->getViolations() as $violation) {
                $errors[] = [
                    'field' => $violation->getPropertyPath(),
                    'value' => $violation->getInvalidValue(),
                    'message' => $violation->getMessage(),
                ];
            }
        }

        $bodyResponse = [
            'code' => $throwable->getCode() ?: $mappedStatusCode,
            'message' => [] !== $errors ? 'Validation failed' : $throwable->getMessage(),
        ];

        if ([] !== $errors) {
            $bodyResponse['errors'] = $errors;
        }

        return new JsonResponse($bodyResponse, $statusCode);
    }
}
