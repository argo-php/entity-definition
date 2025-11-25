<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\MethodDefinition;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final readonly class MethodDefinitionReflectorFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MethodDefinitionReflectorInterface
    {
        $collector = $container->get(MethodDefinitionReflector::class);

        return new MethodDefinitionCacheableReflector($collector);
    }
}
