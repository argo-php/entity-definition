<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\FunctionDefinition;

use Argo\EntityDefinition\Definition\Collection\ParameterDefinitionCollectionInterface;
use Argo\EntityDefinition\Definition\FunctionDefinition;

/**
 * @api
 */
interface FunctionDefinitionReflectorInterface
{
    public function getFunctionDefinition(\ReflectionFunction $reflectionFunction): FunctionDefinition;

    public function getParameters(\ReflectionFunction $reflectionFunction): ParameterDefinitionCollectionInterface;
}
