<?php

declare(strict_types=1);

namespace ArgoTest\EntityDefinition\Reflector\Support;

use Argo\EntityDefinition\Reflector\Support\AttributesReflector;
use ArgoTest\EntityDefinition\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(AttributesReflector::class)]
class AttributesReflectorTest extends TestCase
{
    public function testForClass(): void
    {
        $reflection = new \ReflectionClass(TestClass::class);

        $reflector = new AttributesReflector();
        $attributes = $reflector->getAttributes($reflection);

        $this->assertTrue($attributes->hasByType(AllAttribute::class));
        $this->assertTrue($attributes->hasByType(ClassAttribute::class));
        $this->assertFalse($attributes->hasByType(PropertyAttribute::class));
    }

    public function testForMethod(): void
    {
        $reflection = new \ReflectionMethod(TestClass::class, '__construct');

        $reflector = new AttributesReflector();
        $attributes = $reflector->getAttributes($reflection);

        $this->assertTrue($attributes->hasByType(AllAttribute::class));
        $this->assertTrue($attributes->hasByType(MethodAttribute::class));
        $this->assertFalse($attributes->hasByType(PropertyAttribute::class));
    }

    public function testForProperty(): void
    {
        $reflection = new \ReflectionProperty(TestClass::class, 'testProperty');

        $reflector = new AttributesReflector();
        $attributes = $reflector->getAttributes($reflection);

        $this->assertTrue($attributes->hasByType(AllAttribute::class));
        $this->assertTrue($attributes->hasByType(PropertyAttribute::class));
        $this->assertFalse($attributes->hasByType(ParameterAttribute::class));
        $this->assertFalse($attributes->hasByType(MethodAttribute::class));
    }

    public function testForPromotedProperty(): void
    {
        $reflection = new \ReflectionProperty(TestClass::class, 'testPromotedProperty');

        $reflector = new AttributesReflector();
        $attributes = $reflector->getAttributes($reflection);

        $this->assertTrue($attributes->hasByType(AllAttribute::class));
        $this->assertTrue($attributes->hasByType(PropertyAttribute::class));
        $this->assertFalse($attributes->hasByType(ParameterAttribute::class));
        $this->assertFalse($attributes->hasByType(MethodAttribute::class));
    }

    public function testForPromotedParameter(): void
    {
        $reflection = new \ReflectionParameter([TestClass::class, '__construct'], 'testPromotedProperty');

        $reflector = new AttributesReflector();
        $attributes = $reflector->getAttributes($reflection);

        $this->assertTrue($attributes->hasByType(AllAttribute::class));
        $this->assertTrue($attributes->hasByType(ParameterAttribute::class));
        $this->assertFalse($attributes->hasByType(PropertyAttribute::class));
        $this->assertFalse($attributes->hasByType(MethodAttribute::class));
    }

    public function testForParameter(): void
    {
        $reflection = new \ReflectionParameter([TestClass::class, '__construct'], 'testParameter');

        $reflector = new AttributesReflector();
        $attributes = $reflector->getAttributes($reflection);

        $this->assertTrue($attributes->hasByType(AllAttribute::class));
        $this->assertTrue($attributes->hasByType(ParameterAttribute::class));
        $this->assertFalse($attributes->hasByType(PropertyAttribute::class));
        $this->assertFalse($attributes->hasByType(MethodAttribute::class));
    }
}

#[\Attribute(\Attribute::TARGET_CLASS)]
class ClassAttribute {}

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class PropertyAttribute {}

#[\Attribute(\Attribute::TARGET_METHOD)]
class MethodAttribute {}

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class ParameterAttribute {}

#[\Attribute(\Attribute::TARGET_ALL)]
class AllAttribute {}

#[ClassAttribute]
#[PropertyAttribute]
#[AllAttribute]
class TestClass
{
    #[PropertyAttribute]
    #[ParameterAttribute]
    #[MethodAttribute]
    #[AllAttribute]
    public string $testProperty;

    #[MethodAttribute]
    #[PropertyAttribute]
    #[AllAttribute]
    public function __construct(
        #[PropertyAttribute]
        #[ParameterAttribute]
        #[MethodAttribute]
        #[AllAttribute]
        public string $testPromotedProperty,
        #[PropertyAttribute]
        #[ParameterAttribute]
        #[MethodAttribute]
        #[AllAttribute]
        string $testParameter,
    ) {}
}
