<?php

declare(strict_types=1);

namespace Tenants\Tests\Integration\Shared\Infrastructure\Symfony\Exception;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Tenants\Shared\Infrastructure\Symfony\Exception\ExceptionResponseFactory;
use Tenants\Shared\Infrastructure\Symfony\Exception\ExceptionToHttpStatusMapper;

final class ExceptionResponseFactoryTest extends TestCase
{
    #[Test]
    public function should_return_response_from_generic_exception(): void
    {
        $exception = new RuntimeException('Something went wrong', 1001);

        $factory = new ExceptionResponseFactory(new ExceptionToHttpStatusMapper());
        $response = $factory->createExceptionResponse($exception, 500);

        $content = $response->getContent();
        $this->assertIsString($content);

        /** @var array{code: int, message: string} $data */
        $data = json_decode($content, true);
        $this->assertSame(1001, $data['code']);
        $this->assertSame('Something went wrong', $data['message']);
        $this->assertSame(500, $response->getStatusCode());
    }

    #[Test]
    public function should_return_response_from_validation_exception(): void
    {
        $violations = new ConstraintViolationList([
            new ConstraintViolation('Invalid email', null, [], '', 'email', 'bad-email'),
        ]);

        $validationException = new ValidationFailedException('Validation failed', $violations);
        $exception = new RuntimeException('', 0, $validationException);

        $factory = new ExceptionResponseFactory(new ExceptionToHttpStatusMapper());
        $response = $factory->createExceptionResponse($exception, 400);

        $content = $response->getContent();
        $this->assertIsString($content);

        /**
         * @var array{
         *     code?: int,
         *     message: string,
         *     errors: array<array{field: string, value: mixed, message: string}>
         * } $data
         */
        $data = json_decode($content, true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Validation failed', $data['message']);
        $this->assertCount(1, $data['errors']);
        $this->assertSame('email', $data['errors'][0]['field']);
        $this->assertSame('bad-email', $data['errors'][0]['value']);
        $this->assertSame('Invalid email', $data['errors'][0]['message']);
    }
}
