<?php

declare(strict_types=1);

namespace Tenants\Api;

use Exception;
use Override;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const string CACHE_DIRECTORY_PATH = '/var/cache/apps/api';
    private const string LOG_DIRECTORY_PATH = '/var/logs/api';

    /** @throws Exception */
    public function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $configDirectory = $this->getProjectDir() . '/config';

        $loader->load($configDirectory . '/packages/*.{php,xml,yaml,yml}', 'glob');
        $loader->load($configDirectory . '/packages/**/*.{php,xml,yaml,yml}', 'glob');
        $loader->load($configDirectory . '/services.yaml');
    }

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
