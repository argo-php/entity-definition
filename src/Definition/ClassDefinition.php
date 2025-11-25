<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition;

use Argo\EntityDefinition\Collection\AttributeCollection;
use Argo\EntityDefinition\Collection\TemplateCollection;
use Argo\EntityDefinition\Definition\Collection\MethodDefinitionCollectionInterface;
use Argo\EntityDefinition\Definition\Collection\PropertyDefinitionCollectionInterface;
use Argo\EntityDefinition\Definition\Flag\ClassFlag;

/**
 * @api
 * @template TClass
 */
readonly class ClassDefinition
{
    /**
     * @param class-string<TClass> $className
     * @param int-mask-of<ClassFlag::*> $modifiers
     */
    public function __construct(
        public string $className,
        public \ReflectionClass $reflection,
        public PropertyDefinitionCollectionInterface $properties,
        public MethodDefinitionCollectionInterface $methods,
        public int $modifiers = 0,
        public AttributeCollection $attributes = new AttributeCollection(),
        public TemplateCollection $templates = new TemplateCollection(),
        public ?string $description = null,
    ) {}

    public function isAbstract(): bool
    {
        return ($this->modifiers & ClassFlag::IS_ABSTRACT) !== 0;
    }

    public function isFinal(): bool
    {
        return ($this->modifiers & ClassFlag::IS_FINAL) !== 0;
    }

    public function isReadonly(): bool
    {
        return ($this->modifiers & ClassFlag::IS_READONLY) !== 0;
    }

    public function isDeprecated(): bool
    {
        return ($this->modifiers & ClassFlag::IS_DEPRECATED) !== 0;
    }
}
