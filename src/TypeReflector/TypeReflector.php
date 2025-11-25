<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\TypeReflector;

use Argo\Types\Alias\FalseType;
use Argo\Types\Alias\IterableType;
use Argo\Types\Alias\TrueType;
use Argo\Types\Atomic\ArrayType;
use Argo\Types\Atomic\BoolType;
use Argo\Types\Atomic\CallableType;
use Argo\Types\Atomic\ClassType;
use Argo\Types\Atomic\FloatType;
use Argo\Types\Atomic\IntType;
use Argo\Types\Atomic\MixedType;
use Argo\Types\Atomic\NeverType;
use Argo\Types\Atomic\NullType;
use Argo\Types\Atomic\ObjectType;
use Argo\Types\Atomic\ResourceType;
use Argo\Types\Atomic\StringType;
use Argo\Types\Atomic\VoidType;
use Argo\Types\Complex\IntersectType;
use Argo\Types\Complex\UnionType;
use Argo\Types\TypeInterface;

/**
 * @api
 */
class TypeReflector implements TypeReflectorInterface
{
    public function getType(?\ReflectionType $reflectionType, ?TypeInterface $docBlockType = null): TypeInterface
    {
        if ($reflectionType === null) {
            return $docBlockType ?? new MixedType();
        }

        if ($reflectionType instanceof \ReflectionNamedType) {
            $type = $this->getFromReflectionNamedType($reflectionType);
        } elseif ($reflectionType instanceof \ReflectionUnionType) {
            $type = $this->getFromReflectionUnionType($reflectionType);
        } elseif ($reflectionType instanceof \ReflectionIntersectionType) {
            $type = $this->getFromReflectionIntersectionType($reflectionType);
        } else {
            $type = new MixedType();
        }

        if ($docBlockType !== null && $type->isContravariantTo($docBlockType)) {
            $type = $docBlockType;
        }

        return $type;
    }

    private function getFromReflectionNamedType(\ReflectionNamedType $reflectionType): TypeInterface
    {
        if ($reflectionType->isBuiltin()) {
            $type = $this->getBuiltinType($reflectionType->getName());
        } else {
            $className = ltrim($reflectionType->getName(), '\\');
            $type = new ClassType($className);
        }

        if ($reflectionType->allowsNull()) {
            $type = $type->setNullable();
        }

        return $type;
    }

    private function getBuiltinType(string $name): TypeInterface
    {
        return match ($name) {
            'array' => new ArrayType(),
            'bool' => new BoolType(),
            'callable' => new CallableType(),
            'float' => new FloatType(),
            'int' => new IntType(),
            'iterable' => new IterableType(),
            'never' => new NeverType(),
            'null' => new NullType(),
            'object' => new ObjectType(),
            'resource' => new ResourceType(),
            'string' => new StringType(),
            'void' => new VoidType(),
            'true' => new TrueType(),
            'false' => new FalseType(),
            default => new MixedType(),
        };
    }

    private function getFromReflectionUnionType(\ReflectionUnionType $type): TypeInterface
    {
        $types = $this->getAggregateTypes($type->getTypes());

        return UnionType::mergeTypes(...$types);
    }

    private function getFromReflectionIntersectionType(\ReflectionIntersectionType $type): TypeInterface
    {
        $types = $this->getAggregateTypes($type->getTypes());

        return new IntersectType(...$this->filterTypesForIntersection($types));
    }

    /**
     * @param list<\ReflectionType> $types
     * @return list<TypeInterface>
     */
    private function getAggregateTypes(array $types): array
    {
        return array_map(
            fn(\ReflectionType $type) => $this->getType($type),
            $types,
        );
    }

    /**
     * @param list<TypeInterface> $types
     * @return list<ClassType>
     */
    private function filterTypesForIntersection(array $types): array
    {
        return array_values(
            array_filter(
                $types,
                fn(TypeInterface $type) => $type instanceof ClassType,
            ),
        );
    }
}
