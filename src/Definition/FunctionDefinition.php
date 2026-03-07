<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition;

use Argo\EntityDefinition\Collection\AttributeCollection;
use Argo\EntityDefinition\Collection\TemplateCollection;
use Argo\EntityDefinition\Definition\Collection\ParameterDefinitionCollectionInterface;
use Argo\EntityDefinition\Definition\Flag\MethodFlag;
use Argo\Types\TypeInterface;

/**
 * @api
 */
readonly class FunctionDefinition
{
    /**
     * @param list<TypeInterface> $throws
     * @param int-mask-of<MethodFlag::*> $modifiers
     */
    public function __construct(
        public string $name,
        public \ReflectionFunction $reflection,
        public ParameterDefinitionCollectionInterface $parameters,
        public ValueDefinition $returnValue,
        public int $modifiers = 0,
        public AttributeCollection $attributes = new AttributeCollection(),
        public TemplateCollection $templates = new TemplateCollection(),
        public array $throws = [],
        public ?string $description = null,
    ) {}

    public function isStatic(): bool
    {
        return ($this->modifiers & MethodFlag::IS_STATIC) !== 0;
    }

    public function isDeprecated(): bool
    {
        return ($this->modifiers & MethodFlag::IS_DEPRECATED) !== 0;
    }
}
