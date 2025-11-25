<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition\Collection;

use Argo\DocBlockParser\Tags\ParamTag;
use Argo\EntityDefinition\Definition\ParameterDefinition;
use Argo\EntityDefinition\Reflector\ParameterDefinition\ParameterDefinitionReflectorInterface;

final class ProxyParameterDefinitionCollection implements ParameterDefinitionCollectionInterface
{
    private ?ParameterDefinitionCollection $collection = null;

    /**
     * @param list<list{\ReflectionParameter, ?ParamTag}> $parameterReflections
     */
    public function __construct(
        private readonly array $parameterReflections,
        private readonly ParameterDefinitionReflectorInterface $reflector,
    ) {}

    private function getCollection(): ParameterDefinitionCollection
    {
        if ($this->collection === null) {
            $definitions = array_map(
                fn(array $reflection) => $this->reflector->getParameterDefinition($reflection[0], $reflection[1]),
                $this->parameterReflections,
            );

            $this->collection = new ParameterDefinitionCollection($definitions);
        }

        return $this->collection;
    }

    public function getIterator(): \Traversable
    {
        return $this->getCollection()->getIterator();
    }

    public function count(): int
    {
        return $this->getCollection()->count();
    }

    public function filter(int $filter): iterable
    {
        return $this->getCollection()->filter($filter);
    }

    public function getByName(string $name): ?ParameterDefinition
    {
        return $this->getCollection()->getByName($name);
    }
}
