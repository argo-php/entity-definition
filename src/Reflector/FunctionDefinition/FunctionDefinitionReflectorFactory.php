<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\FunctionDefinition;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final readonly class FunctionDefinitionReflectorFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FunctionDefinitionReflectorInterface
    {
        $collector = $container->get(FunctionDefinitionReflector::class);

        return new FunctionDefinitionCacheableReflector($collector);
    }
}
