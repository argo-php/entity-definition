<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition\Collection;

use Argo\EntityDefinition\Definition\Flag\PropertyFlag;
use Argo\EntityDefinition\Definition\PropertyDefinition;

/**
 * @api
 * @template-extends \IteratorAggregate<int, PropertyDefinition>
 */
interface PropertyDefinitionCollectionInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param int-mask-of<PropertyFlag::*> $filter
     * @return iterable<PropertyDefinition>
     */
    public function filter(int $filter): iterable;

    public function getByName(string $name): ?PropertyDefinition;
}
