<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition;

use Argo\EntityDefinition\Collection\AttributeCollection;
use Argo\EntityDefinition\Definition\Flag\PropertyFlag;
use Argo\Types\TypeInterface;

/**
 * @api
 */
readonly class PropertyDefinition extends ValueDefinition
{
    /**
     * @param int-mask-of<PropertyFlag::*> $modifiers
     */
    public function __construct(
        TypeInterface $type,
        public string $name,
        public \ReflectionProperty $reflection,
        public int $modifiers = 0,
        public bool $hasDefaultValue = false,
        public mixed $defaultValue = null,
        public AttributeCollection $attributes = new AttributeCollection(),
        ?string $description = null,
    ) {
        parent::__construct($type, $description);
    }

    public function isStatic(): bool
    {
        return ($this->modifiers & PropertyFlag::IS_STATIC) !== 0;
    }

    public function isPublic(): bool
    {
        return ($this->modifiers & PropertyFlag::IS_PUBLIC) !== 0;
    }

    public function isProtected(): bool
    {
        return ($this->modifiers & PropertyFlag::IS_PROTECTED) !== 0;
    }

    public function isPrivate(): bool
    {
        return ($this->modifiers & PropertyFlag::IS_PRIVATE) !== 0;
    }

    public function isReadonly(): bool
    {
        return ($this->modifiers & PropertyFlag::IS_READONLY) !== 0;
    }

    public function isDeprecated(): bool
    {
        return ($this->modifiers & PropertyFlag::IS_DEPRECATED) !== 0;
    }

    public function isPromoted(): bool
    {
        return ($this->modifiers & PropertyFlag::IS_PROMOTED) !== 0;
    }
}
