<?php

declare(strict_types=1);

namespace Tenants\Api;

use Override;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const string CACHE_DIRECTORY_PATH = '/var/cache/api';
    private const string LOG_DIRECTORY_PATH = '/var/logs/api';

    #[Override]
    public function getProjectDir(): string
    {
        return dirname(__DIR__);
    }

    #[Override]
    public function getCacheDir(): string
    {
        return $this->getRootPath() . self::CACHE_DIRECTORY_PATH . DIRECTORY_SEPARATOR . $this->environment;
    }

    #[Override]
    public function getLogDir(): string
    {
        return $this->getRootPath() . self::LOG_DIRECTORY_PATH . DIRECTORY_SEPARATOR . $this->environment;
    }

    #[Override]
    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }

    private function getRootPath(): string
    {
        return dirname(__DIR__, 3);
    }
}
