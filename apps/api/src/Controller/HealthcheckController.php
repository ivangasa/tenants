<?php

declare(strict_types=1);

namespace Tenants\Api\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function time;

#[Route('/healthcheck', name: 'healthcheck', methods: [Request::METHOD_GET])]
final readonly class HealthcheckController
{
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse([
            'base_url' => $request->getBaseUrl(),
            'timestamp' => time(),
            'version' => '0.0.1',
            'locale' => $request->getLocale(),
        ]);
    }
}