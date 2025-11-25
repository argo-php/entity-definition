<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\Definition;

use Argo\Types\TypeInterface;

/**
 * @api
 */
readonly class ValueDefinition
{
    public function __construct(
        public TypeInterface $type,
        public ?string $description = null,
    ) {}
}
