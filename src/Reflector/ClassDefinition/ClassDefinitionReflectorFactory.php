<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\ClassDefinition;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final readonly class ClassDefinitionReflectorFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ClassDefinitionReflectorInterface
    {
        $collector = $container->get(ClassDefinitionReflector::class);

        return new ClassDefinitionCacheableReflector($collector);
    }
}
