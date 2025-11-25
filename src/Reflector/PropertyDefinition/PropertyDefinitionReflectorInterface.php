<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\PropertyDefinition;

use Argo\EntityDefinition\Definition\PropertyDefinition;

/**
 * @api
 */
interface PropertyDefinitionReflectorInterface
{
    public function getPropertyDefinition(\ReflectionProperty $reflectionProperty): PropertyDefinition;
}
