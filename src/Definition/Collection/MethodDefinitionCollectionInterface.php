<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition\Collection;

use Argo\EntityDefinition\Definition\Flag\MethodFlag;
use Argo\EntityDefinition\Definition\MethodDefinition;

/**
 * @api
 * @template-extends \IteratorAggregate<int, MethodDefinition>
 */
interface MethodDefinitionCollectionInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param int-mask-of<MethodFlag::*> $filter
     * @return iterable<MethodDefinition>
     */
    public function filter(int $filter): iterable;

    public function getByName(string $name): ?MethodDefinition;

    public function getConstructor(): ?MethodDefinition;

    public function getDestructor(): ?MethodDefinition;
}
