<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition\Collection;

use Argo\EntityDefinition\Definition\ParameterDefinition;

final readonly class ParameterDefinitionCollection implements ParameterDefinitionCollectionInterface
{
    /**
     * @param list<ParameterDefinition> $definitions
     */
    public function __construct(
        private array $definitions,
    ) {}

    public function getIterator(): \Traversable
    {
        foreach ($this->definitions as $definition) {
            yield $definition;
        }
    }

    public function count(): int
    {
        return count($this->definitions);
    }

    public function filter(int $filter): iterable
    {
        foreach ($this->definitions as $definition) {
            if (($definition->modifiers & $filter) === $filter) {
                yield $definition;
            }
        }
    }

    public function getByName(string $name): ?ParameterDefinition
    {
        foreach ($this->definitions as $definition) {
            if ($definition->name === $name) {
                return $definition;
            }
        }

        return null;
    }
}
