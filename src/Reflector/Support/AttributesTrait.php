<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\Support;

use Argo\EntityDefinition\Collection\AttributeCollection;

trait AttributesTrait
{
    private function getAttributes(\Reflector $reflector): AttributeCollection
    {
        $attributes = [];

        if (
            $reflector instanceof \ReflectionMethod
            || $reflector instanceof \ReflectionParameter
            || $reflector instanceof \ReflectionProperty
            || $reflector instanceof \ReflectionClass
        ) {
            $attributes = array_map(
                fn(\ReflectionAttribute $reflectionAttribute) => $reflectionAttribute->newInstance(),
                $reflector->getAttributes(),
            );
        }

        return new AttributeCollection($attributes);
    }
}
