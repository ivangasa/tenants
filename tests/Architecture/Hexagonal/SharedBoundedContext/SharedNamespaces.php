<?php

declare(strict_types=1);

namespace Tenants\Tests\Architecture\Hexagonal\SharedBoundedContext;

final class SharedNamespaces
{
    public const string SharedApplicationLayer = "Tenants\Shared\Application";
    public const string SharedDomainLayer = "Tenants\Shared\Domain";
    public const string SharedInfrastructureLayer = "Tenants\Shared\Infrastructure";
    public const string SharedUiLayer = "Tenants\Shared\Ui";
    public const string NanoIdValueObjectNamespace = "Tenants\Shared\Domain\ValueObject\NanoId";
    public const string DomainBusesNamespace = "Tenants\Shared\Domain\Bus";
}
