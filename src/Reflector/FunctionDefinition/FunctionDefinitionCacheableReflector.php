<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\FunctionDefinition;

use Argo\EntityDefinition\Definition\Collection\ParameterDefinitionCollectionInterface;
use Argo\EntityDefinition\Definition\FunctionDefinition;

/**
 * @api
 */
class FunctionDefinitionCacheableReflector implements FunctionDefinitionReflectorInterface
{
    /** @var array<string, FunctionDefinition> */
    private array $methodDefinitions = [];
    /** @var array<string, ParameterDefinitionCollectionInterface> */
    private array $parameters = [];

    public function __construct(
        private readonly FunctionDefinitionReflectorInterface $reflector,
    ) {}

    public function getFunctionDefinition(\ReflectionFunction $reflectionFunction): FunctionDefinition
    {
        $cacheKey = $this->getCacheKey($reflectionFunction);

        if (!array_key_exists($cacheKey, $this->methodDefinitions)) {
            $this->methodDefinitions[$cacheKey] = $this->reflector->getFunctionDefinition($reflectionFunction);
        }

        return $this->methodDefinitions[$cacheKey];
    }

    public function getParameters(\ReflectionFunction $reflectionFunction): ParameterDefinitionCollectionInterface
    {
        $cacheKey = $this->getCacheKey($reflectionFunction);

        if (array_key_exists($cacheKey, $this->methodDefinitions)) {
            return $this->methodDefinitions[$cacheKey]->parameters;
        }

        if (!array_key_exists($cacheKey, $this->parameters)) {
            $this->parameters[$cacheKey] = $this->reflector->getParameters($reflectionFunction);
        }

        return $this->parameters[$cacheKey];
    }

    private function getCacheKey(\ReflectionFunction $reflectionFunction): string
    {
        return $reflectionFunction->getName();
    }
}
