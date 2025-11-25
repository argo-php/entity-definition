<?php

declare(strict_types=1);

namespace ArgoTest\EntityDefinition\Definition;

use Argo\EntityDefinition\Definition\Flag\ParameterFlag;
use Argo\EntityDefinition\Definition\ParameterDefinition;
use Argo\Types\Atomic\MixedType;
use ArgoTest\EntityDefinition\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(ParameterDefinition::class)]
class ParameterDefinitionTest extends TestCase
{
    use ModifierGenerator;

    public static function modifiersDataProvider(): iterable
    {
        return self::generateModifiers([
            ParameterFlag::IS_VARIADIC,
            ParameterFlag::IS_PROMOTED,
            ParameterFlag::IS_DEPRECATED,
        ]);
    }

    #[DataProvider('modifiersDataProvider')]
    public function testModifiers(
        int $modifiers,
        bool $isVariadic,
        bool $isPromoted,
        bool $isDeprecated,
    ): void {
        $definition = new ParameterDefinition(
            new MixedType(),
            'test',
            \Mockery::mock(\ReflectionParameter::class),
            modifiers: $modifiers,
        );

        $this->assertEquals($isVariadic, $definition->isVariadic());
        $this->assertEquals($isPromoted, $definition->isPromoted());
        $this->assertEquals($isDeprecated, $definition->isDeprecated());
    }
}
