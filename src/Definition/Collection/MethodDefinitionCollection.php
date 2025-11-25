<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition\Collection;

use Argo\EntityDefinition\Definition\MethodDefinition;

final readonly class MethodDefinitionCollection implements MethodDefinitionCollectionInterface
{
    /**
     * @param list<MethodDefinition> $definitions
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

    public function getByName(string $name): ?MethodDefinition
    {
        foreach ($this->definitions as $definition) {
            if ($definition->methodName === $name) {
                return $definition;
            }
        }

        return null;
    }

    public function filter(int $filter): iterable
    {
        foreach ($this->definitions as $definition) {
            if (($definition->modifiers & $filter) === $filter) {
                yield $definition;
            }
        }
    }

    public function getConstructor(): ?MethodDefinition
    {
        foreach ($this->definitions as $definition) {
            if ($definition->isConstructor()) {
                return $definition;
            }
        }

        return null;
    }

    public function getDestructor(): ?MethodDefinition
    {
        foreach ($this->definitions as $definition) {
            if ($definition->isDestructor()) {
                return $definition;
            }
        }

        return null;
    }
}
