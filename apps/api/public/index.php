<?php

declare(strict_types=1);

use Tenants\Api\Kernel;

require_once dirname(__DIR__, 3) . '/vendor/autoload_runtime.php';

return static function (array $context): Kernel {
    /** @var string $env */
    $env = $context['APP_ENV'];

    /** @var bool $debug */
    $debug = filter_var($context['APP_DEBUG'], \FILTER_VALIDATE_BOOLEAN);

    return new Kernel($env, $debug);
};
