<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Reflector\FunctionDefinition;

use Argo\DocBlockParser\PhpDoc;
use Argo\DocBlockParser\PhpDocFactory;
use Argo\DocBlockParser\Tags\DeprecatedTag;
use Argo\DocBlockParser\Tags\ParamTag;
use Argo\DocBlockParser\Tags\ReturnTag;
use Argo\DocBlockParser\Tags\ThrowsTag;
use Argo\EntityDefinition\Collection\AttributeCollection;
use Argo\EntityDefinition\Definition\Collection\ParameterDefinitionCollectionInterface;
use Argo\EntityDefinition\Definition\Collection\ProxyParameterDefinitionCollection;
use Argo\EntityDefinition\Definition\Flag\MethodFlag;
use Argo\EntityDefinition\Definition\FunctionDefinition;
use Argo\EntityDefinition\Definition\ValueDefinition;
use Argo\EntityDefinition\Reflector\ParameterDefinition\ParameterDefinitionReflectorInterface;
use Argo\EntityDefinition\Reflector\Support\AttributesTrait;
use Argo\EntityDefinition\Reflector\Support\TemplatesTrait;
use Argo\EntityDefinition\TypeReflector\TypeReflectorInterface;
use Argo\Types\TypeInterface;

/**
 * @api
 */
readonly class FunctionDefinitionReflector implements FunctionDefinitionReflectorInterface
{
    use AttributesTrait;
    use TemplatesTrait;

    public function __construct(
        private PhpDocFactory $phpDocFactory,
        private TypeReflectorInterface $typeReflector,
        private ParameterDefinitionReflectorInterface $parameterReflector,
    ) {}

    public function getFunctionDefinition(\ReflectionFunction $reflectionFunction): FunctionDefinition
    {
        $docBlock = $this->phpDocFactory->getPhpDocFromReflector($reflectionFunction);
        $attributes = $this->getAttributes($reflectionFunction);

        return new FunctionDefinition(
            name: $reflectionFunction->getName(),
            reflection: $reflectionFunction,
            parameters: $this->getParameters($reflectionFunction, $docBlock),
            returnValue: $this->getReturnValueFromReflector($reflectionFunction->getReturnType(), $docBlock),
            modifiers: $this->getModifiers($reflectionFunction, $attributes, $docBlock),
            attributes: $attributes,
            templates: $this->getTemplates($docBlock),
            throws: $this->getThrowable($docBlock),
            description: $docBlock?->getDescription(),
        );
    }

    private function getReturnValueFromReflector(
        ?\ReflectionType $reflectionType,
        ?PhpDoc $docBlock,
    ): ValueDefinition {
        $docBlockReturnType = null;
        $description = null;

        if ($docBlock !== null) {
            $returnTag = $docBlock->firstTagByType(ReturnTag::class);
            if ($returnTag !== null) {
                $docBlockReturnType = $returnTag->type;
                $description = $returnTag->description;
            }
        }

        return new ValueDefinition(
            type: $this->typeReflector->getType($reflectionType, $docBlockReturnType),
            description: $description,
        );
    }

    /**
     * @return list<TypeInterface>
     */
    private function getThrowable(?PhpDoc $docBlock): array
    {
        return array_map(
            fn(ThrowsTag $tag) => $tag->type,
            $docBlock?->getTagsByType(ThrowsTag::class) ?? [],
        );
    }

    public function getParameters(
        \ReflectionFunction $reflectionFunction,
        ?PhpDoc $docBlock = null,
    ): ParameterDefinitionCollectionInterface {
        $parameterReflections = array_map(
            fn(\ReflectionParameter $reflectionParameter) => [
                $reflectionParameter,
                $this->findParameterTag($docBlock, $reflectionParameter->getName()),
            ],
            $reflectionFunction->getParameters(),
        );

        return new ProxyParameterDefinitionCollection($parameterReflections, $this->parameterReflector);
    }

    private function findParameterTag(?PhpDoc $docBlock, string $parameterName): ?ParamTag
    {
        if ($docBlock === null) {
            return null;
        }

        $paramTags = $docBlock->getTagsByType(ParamTag::class);

        foreach ($paramTags as $tag) {
            if ($tag->name === $parameterName) {
                return $tag;
            }
        }

        return null;
    }

    /**
     * @return int-mask-of<MethodFlag::*>
     */
    private function getModifiers(
        \ReflectionFunction $reflection,
        AttributeCollection $attributes,
        ?PhpDoc $docBlock,
    ): int {
        $modifiers = 0;
        if ($reflection->isStatic()) {
            $modifiers |= MethodFlag::IS_STATIC;
        }
        /** @psalm-suppress ArgumentTypeCoercion,UndefinedClass */
        if (
            $attributes->hasByType('\JetBrains\PhpStorm\Deprecated')
            || $attributes->hasByType('\Deprecated')
            || $docBlock?->hasTagsByType(DeprecatedTag::class)
        ) {
            $modifiers |= MethodFlag::IS_DEPRECATED;
        }

        return $modifiers;
    }
}
