<?php

declare(strict_types=1);

namespace Argo\EntityDefinition\TypeReflector;

use Argo\Types\Alias\FloatConstType;
use Argo\Types\Alias\IntConstType;
use Argo\Types\Alias\ListType;
use Argo\Types\Alias\StringConstType;
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
use Argo\Types\Atomic\StringType;
use Argo\Types\Misc\KeyShapeItem;
use Argo\Types\Misc\KeyShapeItems;
use Argo\Types\Misc\ShapeItem;
use Argo\Types\Misc\ShapeItems;
use Argo\Types\TypeInterface;

/**
 * @api
 */
class VariableTypeReflector implements VariableTypeReflectorInterface
{
    /**
     * @template TValueType
     * @param TValueType $variable
     * @return TypeInterface<TValueType>
     * @psalm-suppress NoValue
     */
    public function getVariableType(mixed $variable, bool $atomic = false): TypeInterface
    {
        /** @var TypeInterface<TValueType> */
        return match (true) {
            is_null($variable) => new NullType(),
            is_int($variable) => $atomic ? new IntType() : new IntConstType($variable),
            is_float($variable) => $atomic ? new FloatType() : new FloatConstType($variable),
            is_string($variable) => $atomic ? new StringType() : new StringConstType($variable),
            is_bool($variable) => $atomic ? new BoolType() : new BoolType($variable),
            is_array($variable) => $atomic ? new ArrayType() : $this->getArrayType($variable),
            is_object($variable) => $this->getObjectType($variable),
            default => new MixedType(),
        };
    }

    /**
     * @template TArrayType of array
     * @psalm-param TArrayType $variable
     * @return ArrayType<TArrayType>
     */
    private function getArrayType(array $variable): ArrayType
    {
        if ($variable === []) {
            /** @var ArrayType<TArrayType> */
            return new ListType(new NeverType());
        }

        if (array_is_list($variable)) {
            /** @psalm-suppress InvalidArgument */
            $shapeItems = new ShapeItems(
                ...array_map(
                    fn(mixed $item) => new ShapeItem($this->getVariableType($item, true)),
                    $variable,
                ),
            );

            /** @var ArrayType<TArrayType> */
            return new ListType(new NeverType(), $shapeItems);
        }

        $shapes = [];
        foreach ($variable as $key => $value) {
            $shapes[] = new KeyShapeItem($key, $this->getVariableType($value, true));
        }
        /** @psalm-suppress InvalidArgument */
        $shapeItems = new KeyShapeItems(...$shapes);

        /** @var ArrayType<TArrayType> */
        return new ArrayType(new NeverType(), new NeverType(), $shapeItems);
    }

    /**
     * @template TObjectType of object
     * @psalm-param TObjectType $variable
     * @return (TObjectType is \stdClass ? ObjectType : (TObjectType is \Closure ? CallableType : ClassType<TObjectType>))
     */
    private function getObjectType(object $variable): ObjectType|CallableType|ClassType
    {
        if ($variable::class === \stdClass::class) {
            return new ObjectType();
        }
        if ($variable::class === \Closure::class) {
            return new CallableType();
        }

        return new ClassType($variable::class);
    }
}
