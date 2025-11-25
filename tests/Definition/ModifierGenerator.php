<?php

declare(strict_types=1);

namespace ArgoTest\EntityDefinition\Definition;

trait ModifierGenerator
{
    private static function generateModifiers(array $flags, int $modifier = 0, array $results = []): array
    {
        $flag = array_shift($flags);
        if ($flag === null) {
            return [
                [$modifier, ...$results],
            ];
        }

        $cases = [];
        $cases[] = self::generateModifiers($flags, $modifier, array_merge($results, [false]));
        $cases[] = self::generateModifiers($flags, $modifier | $flag, array_merge($results, [true]));

        return array_merge(...$cases);
    }
}
