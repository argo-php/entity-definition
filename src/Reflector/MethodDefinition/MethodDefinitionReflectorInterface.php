<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\MethodDefinition;

use Argo\EntityDefinition\Definition\Collection\ParameterDefinitionCollectionInterface;
use Argo\EntityDefinition\Definition\MethodDefinition;

/**
 * @api
 */
interface MethodDefinitionReflectorInterface
{
    public function getMethodDefinition(\ReflectionMethod $reflectionMethod): MethodDefinition;

    public function getParameters(\ReflectionMethod $reflectionMethod): ParameterDefinitionCollectionInterface;
}
