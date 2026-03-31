<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\Support;

use Argo\EntityDefinition\Collection\AttributeCollection;

final readonly class AttributesReflector
{
    public function getAttributes(\Reflector $reflector): AttributeCollection
    {
        if (
            !$reflector instanceof \ReflectionClass
            && !$reflector instanceof \ReflectionFunctionAbstract
            && !$reflector instanceof \ReflectionParameter
            && !$reflector instanceof \ReflectionProperty
            && !$reflector instanceof \ReflectionClassConstant
        ) {
            return new AttributeCollection();
        }

        $target = match (true) {
            $reflector instanceof \ReflectionClass => \Attribute::TARGET_CLASS,
            $reflector instanceof \ReflectionMethod => \Attribute::TARGET_METHOD,
            $reflector instanceof \ReflectionFunction => \Attribute::TARGET_FUNCTION,
            $reflector instanceof \ReflectionParameter => \Attribute::TARGET_PARAMETER,
            $reflector instanceof \ReflectionProperty => \Attribute::TARGET_PROPERTY,
            $reflector instanceof \ReflectionClassConstant => \Attribute::TARGET_CLASS_CONSTANT,
            default => null,
        };

        if ($target === null) {
            return new AttributeCollection();
        }

        $attributes = [];
        foreach ($reflector->getAttributes() as $attributeReflection) {
            /** @var class-string $attributeName */
            $attributeName = $attributeReflection->getName();
            if ($this->isAttributeAllowed($attributeName, $target)) {
                $attributes[] = $attributeReflection->newInstance();
            }
        }

        return new AttributeCollection($attributes);
    }

    /**
     * @param class-string $attributeClass
     */
    private function isAttributeAllowed(string $attributeClass, int $target): bool
    {
        try {
            $attributeClassReflection = new \ReflectionClass($attributeClass);
            $meta = $attributeClassReflection->getAttributes(\Attribute::class)[0] ?? null;

            if (!$meta) {
                return true;
            }

            $flags = $meta->newInstance()->flags;

            return (bool) ($flags & $target);
        } catch (\ReflectionException) {
            return false;
        }
    }
}
