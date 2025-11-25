<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\ParameterDefinition;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class ParameterDefinitionReflectorFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ParameterDefinitionReflectorInterface
    {
        $collector = $container->get(ParameterDefinitionReflector::class);

        return new ParameterDefinitionCacheableReflector($collector);
    }
}
