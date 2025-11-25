<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\MethodDefinition;

use Argo\EntityDefinition\Definition\Collection\ParameterDefinitionCollectionInterface;
use Argo\EntityDefinition\Definition\MethodDefinition;

/**
 * @api
 */
class MethodDefinitionCacheableReflector implements MethodDefinitionReflectorInterface
{
    /** @var array<string, MethodDefinition> */
    private array $methodDefinitions = [];
    /** @var array<string, ParameterDefinitionCollectionInterface> */
    private array $parameters = [];

    public function __construct(
        private readonly MethodDefinitionReflectorInterface $reflector,
    ) {}

    public function getMethodDefinition(\ReflectionMethod $reflectionMethod): MethodDefinition
    {
        $cacheKey = $this->getCacheKey($reflectionMethod);

        if (!array_key_exists($cacheKey, $this->methodDefinitions)) {
            $this->methodDefinitions[$cacheKey] = $this->reflector->getMethodDefinition($reflectionMethod);
        }

        return $this->methodDefinitions[$cacheKey];
    }

    public function getParameters(\ReflectionMethod $reflectionMethod): ParameterDefinitionCollectionInterface
    {
        $cacheKey = $this->getCacheKey($reflectionMethod);

        if (array_key_exists($cacheKey, $this->methodDefinitions)) {
            return $this->methodDefinitions[$cacheKey]->parameters;
        }

        if (!array_key_exists($cacheKey, $this->parameters)) {
            $this->parameters[$cacheKey] = $this->reflector->getParameters($reflectionMethod);
        }

        return $this->parameters[$cacheKey];
    }

    private function getCacheKey(\ReflectionMethod $reflectionMethod): string
    {
        return $reflectionMethod->getDeclaringClass()->getName() . '::' . $reflectionMethod->getName();
    }
}
