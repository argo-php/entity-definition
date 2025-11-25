<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\ClassDefinition;

use JetBrains\PhpStorm\Deprecated;
use Argo\DocBlockParser\PhpDoc;
use Argo\DocBlockParser\PhpDocFactory;
use Argo\DocBlockParser\Tags\DeprecatedTag;
use Argo\EntityDefinition\Collection\AttributeCollection;
use Argo\EntityDefinition\Definition\ClassDefinition;
use Argo\EntityDefinition\Definition\Collection\MethodDefinitionCollectionInterface;
use Argo\EntityDefinition\Definition\Collection\PropertyDefinitionCollectionInterface;
use Argo\EntityDefinition\Definition\Collection\ProxyMethodDefinitionCollection;
use Argo\EntityDefinition\Definition\Collection\ProxyPropertyDefinitionCollection;
use Argo\EntityDefinition\Definition\Flag\ClassFlag;
use Argo\EntityDefinition\Reflector\MethodDefinition\MethodDefinitionReflectorInterface;
use Argo\EntityDefinition\Reflector\PropertyDefinition\PropertyDefinitionReflectorInterface;
use Argo\EntityDefinition\Reflector\Support\AttributesTrait;
use Argo\EntityDefinition\Reflector\Support\TemplatesTrait;

/**
 * @api
 */
readonly class ClassDefinitionReflector implements ClassDefinitionReflectorInterface
{
    use AttributesTrait;
    use TemplatesTrait;

    public function __construct(
        private PhpDocFactory $phpDocFactory,
        private MethodDefinitionReflectorInterface $methodReflector,
        private PropertyDefinitionReflectorInterface $propertyReflector,
    ) {}

    public function getClassDefinition(\ReflectionClass $reflectionClass): ClassDefinition
    {
        $attributes = $this->getAttributes($reflectionClass);
        $docBlockClass = $this->phpDocFactory->getPhpDocFromReflector($reflectionClass);

        return new ClassDefinition(
            className: $reflectionClass->getName(),
            reflection: $reflectionClass,
            properties: $this->getProperties($reflectionClass),
            methods: $this->getMethods($reflectionClass),
            modifiers: $this->getModifiers($reflectionClass, $attributes, $docBlockClass),
            attributes: $attributes,
            templates: $this->getTemplates($docBlockClass),
            description: $docBlockClass?->getDescription(),
        );
    }

    public function getProperties(\ReflectionClass $reflectionClass): PropertyDefinitionCollectionInterface
    {
        return new ProxyPropertyDefinitionCollection($reflectionClass->getProperties(), $this->propertyReflector);
    }

    public function getMethods(\ReflectionClass $reflectionClass): MethodDefinitionCollectionInterface
    {
        return new ProxyMethodDefinitionCollection($reflectionClass->getMethods(), $this->methodReflector);
    }

    /**
     * @return int-mask-of<ClassFlag::*>
     */
    private function getModifiers(\ReflectionClass $reflection, AttributeCollection $attributes, ?PhpDoc $docBlock): int
    {
        $modifiers = 0;
        if ($reflection->isAbstract()) {
            $modifiers |= ClassFlag::IS_ABSTRACT;
        }
        if ($reflection->isFinal()) {
            $modifiers |= ClassFlag::IS_FINAL;
        }
        if ($reflection->isReadOnly()) {
            $modifiers |= ClassFlag::IS_READONLY;
        }
        if ($attributes->hasByType(Deprecated::class) || $docBlock?->hasTagsByType(DeprecatedTag::class)) {
            $modifiers |= ClassFlag::IS_DEPRECATED;
        }

        return $modifiers;
    }
}
