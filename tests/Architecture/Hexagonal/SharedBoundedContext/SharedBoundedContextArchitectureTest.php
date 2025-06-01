<?php

declare(strict_types=1);

namespace Tenants\Tests\Architecture\Hexagonal\SharedBoundedContext;

use PHPat\Selector\Selector;
use PHPat\Test\Attributes\TestRule;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class SharedBoundedContextArchitectureTest
{
    #[TestRule]
    public function should_follow_application_layer_rules(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace(SharedNamespaces::SharedApplicationLayer))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace(SharedNamespaces::SharedInfrastructureLayer),
                Selector::inNamespace(SharedNamespaces::SharedUiLayer)
            );
    }

    #[TestRule]
    public function should_follow_domain_layer_rules(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace(SharedNamespaces::SharedDomainLayer))
            ->excluding(Selector::classname(SharedNamespaces::NanoIdValueObjectNamespace))
            ->canOnlyDependOn()
            ->classes(Selector::inNamespace(SharedNamespaces::SharedDomainLayer));
    }

    public function should_follow_infrastructure_layer_rules(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace(SharedNamespaces::SharedInfrastructureLayer))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace(SharedNamespaces::SharedApplicationLayer),
                Selector::inNamespace(SharedNamespaces::SharedDomainLayer),
                Selector::inNamespace(SharedNamespaces::SharedUiLayer)
            );
    }

    #[TestRule]
    public function should_follow_ui_layer_rules(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace(SharedNamespaces::SharedUiLayer))
            ->canOnlyDependOn()
            ->classes(
                Selector::inNamespace(SharedNamespaces::SharedApplicationLayer),
                Selector::inNamespace(SharedNamespaces::SharedUiLayer),
                Selector::inNamespace(SharedNamespaces::DomainBusesNamespace)
            );
    }
}
