<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\ClassDefinition;

use Argo\EntityDefinition\Definition\ClassDefinition;
use Argo\EntityDefinition\Definition\Collection\MethodDefinitionCollectionInterface;
use Argo\EntityDefinition\Definition\Collection\PropertyDefinitionCollectionInterface;

/**
 * @api
 */
class ClassDefinitionCacheableReflector implements ClassDefinitionReflectorInterface
{
    /** @var array<string, ClassDefinition> */
    private array $classDefinitions = [];
    /** @var array<string, PropertyDefinitionCollectionInterface> */
    private array $properties = [];
    /** @var array<string, MethodDefinitionCollectionInterface> */
    private array $methods = [];

    public function __construct(
        private readonly ClassDefinitionReflectorInterface $reflector,
    ) {}

    public function getClassDefinition(\ReflectionClass $reflectionClass): ClassDefinition
    {
        $cacheKey = $this->getCacheKey($reflectionClass);

        if (!array_key_exists($cacheKey, $this->classDefinitions)) {
            $this->classDefinitions[$cacheKey] = $this->reflector->getClassDefinition($reflectionClass);
        }

        return $this->classDefinitions[$cacheKey];
    }

    public function getProperties(\ReflectionClass $reflectionClass): PropertyDefinitionCollectionInterface
    {
        $cacheKey = $this->getCacheKey($reflectionClass);

        if (array_key_exists($cacheKey, $this->classDefinitions)) {
            return $this->classDefinitions[$cacheKey]->properties;
        }

        if (!array_key_exists($cacheKey, $this->properties)) {
            $this->properties[$cacheKey] = $this->reflector->getProperties($reflectionClass);
        }

        return $this->properties[$cacheKey];
    }

    public function getMethods(\ReflectionClass $reflectionClass): MethodDefinitionCollectionInterface
    {
        $cacheKey = $this->getCacheKey($reflectionClass);

        if (array_key_exists($cacheKey, $this->classDefinitions)) {
            return $this->classDefinitions[$cacheKey]->methods;
        }

        if (!array_key_exists($cacheKey, $this->methods)) {
            $this->methods[$cacheKey] = $this->reflector->getMethods($reflectionClass);
        }

        return $this->methods[$cacheKey];
    }

    public function getCacheKey(\ReflectionClass $reflectionClass): string
    {
        return $reflectionClass->getName();
    }
}
