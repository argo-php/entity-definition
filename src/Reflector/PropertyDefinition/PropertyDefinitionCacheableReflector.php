<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\PropertyDefinition;

use Argo\EntityDefinition\Definition\PropertyDefinition;

/**
 * @api
 */
class PropertyDefinitionCacheableReflector implements PropertyDefinitionReflectorInterface
{
    /** @var array<string, PropertyDefinition> */
    private array $propertyDefinitions = [];

    public function __construct(
        private readonly PropertyDefinitionReflectorInterface $reflector,
    ) {}

    public function getPropertyDefinition(\ReflectionProperty $reflectionProperty): PropertyDefinition
    {
        $cacheKey = $this->getCacheKey($reflectionProperty);

        if (!array_key_exists($cacheKey, $this->propertyDefinitions)) {
            $this->propertyDefinitions[$cacheKey] = $this->reflector->getPropertyDefinition($reflectionProperty);
        }

        return $this->propertyDefinitions[$cacheKey];
    }

    private function getCacheKey(\ReflectionProperty $reflectionProperty): string
    {
        return sprintf(
            '%s@%s',
            $reflectionProperty->getDeclaringClass()->getName(),
            $reflectionProperty->getName(),
        );
    }
}
