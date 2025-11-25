<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\TypeReflector;

use Argo\Types\TypeInterface;

/**
 * @api
 */
interface TypeReflectorInterface
{
    public function getType(?\ReflectionType $reflectionType, ?TypeInterface $docBlockType = null): TypeInterface;
}
