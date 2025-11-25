<?php

declare(strict_types=1);

namespace Argo\EntityDefinition;

use Argo\EntityDefinition\Reflector\ClassDefinition\ClassDefinitionReflectorFactory;
use Argo\EntityDefinition\Reflector\ClassDefinition\ClassDefinitionReflectorInterface;
use Argo\EntityDefinition\Reflector\MethodDefinition\MethodDefinitionReflectorFactory;
use Argo\EntityDefinition\Reflector\MethodDefinition\MethodDefinitionReflectorInterface;
use Argo\EntityDefinition\Reflector\ParameterDefinition\ParameterDefinitionReflectorFactory;
use Argo\EntityDefinition\Reflector\ParameterDefinition\ParameterDefinitionReflectorInterface;
use Argo\EntityDefinition\Reflector\PropertyDefinition\PropertyDefinitionReflectorFactory;
use Argo\EntityDefinition\Reflector\PropertyDefinition\PropertyDefinitionReflectorInterface;
use Argo\EntityDefinition\TypeReflector\TypeReflector;
use Argo\EntityDefinition\TypeReflector\TypeReflectorInterface;
use Argo\EntityDefinition\TypeReflector\VariableTypeReflector;
use Argo\EntityDefinition\TypeReflector\VariableTypeReflectorInterface;

/**
 * @api
 */
class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                ClassDefinitionReflectorInterface::class => ClassDefinitionReflectorFactory::class,
                MethodDefinitionReflectorInterface::class => MethodDefinitionReflectorFactory::class,
                PropertyDefinitionReflectorInterface::class => PropertyDefinitionReflectorFactory::class,
                ParameterDefinitionReflectorInterface::class => ParameterDefinitionReflectorFactory::class,
                TypeReflectorInterface::class => TypeReflector::class,
                VariableTypeReflectorInterface::class => VariableTypeReflector::class,
            ],
        ];
    }
}
