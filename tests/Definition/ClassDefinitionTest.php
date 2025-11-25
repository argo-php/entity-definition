<?php

declare(strict_types=1);

namespace ArgoTest\EntityDefinition\Definition;

use Argo\EntityDefinition\Definition\ClassDefinition;
use Argo\EntityDefinition\Definition\Collection\MethodDefinitionCollection;
use Argo\EntityDefinition\Definition\Collection\PropertyDefinitionCollection;
use Argo\EntityDefinition\Definition\Flag\ClassFlag;
use ArgoTest\EntityDefinition\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(ClassDefinition::class)]
class ClassDefinitionTest extends TestCase
{
    use ModifierGenerator;

    public static function modifiersDataProvider(): iterable
    {
        return self::generateModifiers([
            ClassFlag::IS_ABSTRACT,
            ClassFlag::IS_FINAL,
            ClassFlag::IS_READONLY,
            ClassFlag::IS_DEPRECATED,
        ]);
    }

    #[DataProvider('modifiersDataProvider')]
    public function testModifiers(
        int $modifiers,
        bool $isAbstract,
        bool $isFinal,
        bool $isReadonly,
        bool $isDeprecated,
    ): void {
        $definition = new ClassDefinition(
            'test',
            \Mockery::mock(\ReflectionClass::class),
            new PropertyDefinitionCollection([]),
            new MethodDefinitionCollection([]),
            modifiers: $modifiers,
        );

        $this->assertEquals($isAbstract, $definition->isAbstract());
        $this->assertEquals($isFinal, $definition->isFinal());
        $this->assertEquals($isReadonly, $definition->isReadonly());
        $this->assertEquals($isDeprecated, $definition->isDeprecated());
    }
}
