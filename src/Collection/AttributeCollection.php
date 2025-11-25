<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Collection;

/**
 * @api
 */
readonly class AttributeCollection
{
    /**
     * @param array<object> $attributes
     */
    public function __construct(private array $attributes = []) {}

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function merge(AttributeCollection $attributes): AttributeCollection
    {
        return new self(array_merge($this->attributes, $attributes->attributes));
    }

    /**
     * @template TType
     * @param class-string<TType> $type
     * @return list<TType>
     */
    public function getByType(string $type): array
    {
        return array_values(
            array_filter(
                $this->attributes,
                fn(object $attribute) => is_a($attribute, $type),
            ),
        );
    }

    /**
     * @template TType
     * @param class-string<TType> $type
     * @return TType|null
     */
    public function firstByType(string $type): ?object
    {
        foreach ($this->attributes as $attribute) {
            if (is_a($attribute, $type)) {
                return $attribute;
            }
        }

        return null;
    }

    /**
     * @param class-string $type
     */
    public function hasByType(string $type): bool
    {
        foreach ($this->attributes as $attribute) {
            if (is_a($attribute, $type)) {
                return true;
            }
        }

        return false;
    }
}
