<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\ParameterDefinition;

use Argo\DocBlockParser\Tags\ParamTag;
use Argo\EntityDefinition\Definition\ParameterDefinition;

/**
 * @api
 */
interface ParameterDefinitionReflectorInterface
{
    public function getParameterDefinition(
        \ReflectionParameter $reflectionParameter,
        ?ParamTag $docBlockParam = null,
    ): ParameterDefinition;
}
