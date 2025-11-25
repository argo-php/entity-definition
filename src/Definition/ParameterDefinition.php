<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition;

use Argo\EntityDefinition\Collection\AttributeCollection;
use Argo\EntityDefinition\Definition\Flag\ParameterFlag;
use Argo\Types\TypeInterface;

/**
 * @api
 */
readonly class ParameterDefinition extends ValueDefinition
{
    /**
     * @param int-mask-of<ParameterFlag::*> $modifiers
     */
    public function __construct(
        TypeInterface $type,
        public string $name,
        public \ReflectionParameter $reflection,
        public int $modifiers = 0,
        public bool $hasDefaultValue = false,
        public mixed $defaultValue = null,
        public AttributeCollection $attributes = new AttributeCollection(),
        ?string $description = null,
    ) {
        parent::__construct($type, $description);
    }

    public function isVariadic(): bool
    {
        return ($this->modifiers & ParameterFlag::IS_VARIADIC) !== 0;
    }

    public function isPromoted(): bool
    {
        return ($this->modifiers & ParameterFlag::IS_PROMOTED) !== 0;
    }

    public function isDeprecated(): bool
    {
        return ($this->modifiers & ParameterFlag::IS_DEPRECATED) !== 0;
    }
}
