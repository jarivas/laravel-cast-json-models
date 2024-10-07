<?php

declare(strict_types=1);

namespace CastModels;

use ReflectionProperty;
use JsonSerializable;
use stdClass;
use ReflectionNamedType;

abstract class Model implements JsonSerializable
{


    /**
     * @param array<array<string, mixed>>|string $data
     * @return static[]
     * */
    public static function collection(array|string $data): array
    {
        $collection = [];

        if (empty($data)) {
            return $collection;
        }

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        // @phpstan-ignore-next-line
        return array_map(fn($item) => new static($item), $data);

    }//end collection()


    /** @param array<string, mixed>|stdClass $data */
    public function __construct(array|stdClass $data=[])
    {
        $this->update($data);

    }//end __construct()


    /** @param array<string, mixed>|stdClass $data */
    public function update(array|stdClass $data=[]): void
    {
        if (empty($data)) {
            return;
        }

        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        foreach ($data as $k => $v) {
            if (isset($v) && property_exists($this, $k)) {
                self::setProperty($this, $k, $v);
            }
        }

    }//end update()


    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $result = [];

        foreach (get_object_vars($this) as $property => $value) {
            $result[$property] = self::toArrayHelper($value);
        }

        return $result;

    }//end toArray()


    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->toArray();

    }//end jsonSerialize()


    /** @return string */
    public function __toString()
    {
        $result = json_encode($this);

        return empty($result) ? '' : $result;

    }//end __toString()


    private static function setProperty(Model $instance, string $propertyName, mixed $value): void
    {
        $rProperty = new ReflectionProperty($instance, $propertyName);
        $rType     = $rProperty->getType();

        if (empty($rType) || !$rType instanceof ReflectionNamedType) {
            return;
        }

        if ($rType->isBuiltin()) {
            self::setPropertyBuiltIn($instance, $rProperty, $propertyName, $value);
            return;
        }

        self::setPropertyHelper($instance, $rType->getName(), $propertyName, $value);

    }//end setProperty()


    private static function setPropertyBuiltIn(Model $instance, ReflectionProperty $rProperty, string $propertyName, mixed $value): void
    {
        if (is_object($value)) {
            $value = (array) $value;
        }

        if (is_array($value)) {
            self::setArray($instance, $rProperty, $propertyName, $value);
            return;
        }

        $instance->$propertyName = $value;

    }//end setPropertyBuiltIn()


    private static function setArray(Model $instance, ReflectionProperty $rProperty, string $propertyName, mixed $value): void
    {
        if (empty($value)) {
            return;
        }

        $phpDoc = $rProperty->getDocComment();

        if (!$phpDoc) {
            $instance->$propertyName = $value;
            return;
        }

        $className = self::getClassNameFromPhpDoc($phpDoc);

        if (is_bool($className)) {
            $instance->$propertyName = $value;
            return;
        }

        if (method_exists($className, 'tryFrom')) {
            $instance->$propertyName = array_map(fn($v) => $className::tryFrom($v), $value);
            return;
        }

        $instance->$propertyName = array_map(fn($v) => new $className($v), $value);

    }//end setArray()


    private static function getClassNameFromPhpDoc(string $phpDoc): string|false
    {
        $pattern = '/\s+([\w|\\\\]+)\[/m';
        $matches = [];

        preg_match($pattern, $phpDoc, $matches, PREG_OFFSET_CAPTURE);

        return empty($matches) ? false : $matches[1][0];

    }//end getClassNameFromPhpDoc()


    private static function setPropertyHelper(Model $instance, string $type, string $propertyName, mixed $value): void
    {
        if (method_exists($type, 'tryFrom')) {
            $instance->$propertyName = $type::tryFrom($value);
            return;
        }

        $type = new $type();

        if ($type instanceof Model) {
            $instance->$propertyName = new $type($value);
        }

    }//end setPropertyHelper()


    /**
     * @param mixed[] $value
     * @return mixed[]
     * */
    private static function toArrayHelper(mixed $value): mixed
    {
        if (is_array($value)) {
            return self::toArrayHelperArray($value);
        }

        // @phpstan-ignore-next-line
        if (!is_object($value)) {
            return $value;
        }

        if ($value instanceof Model) {
            return $value->toArray();
        }

        if (method_exists($value, 'tryFrom')) {
            return $value->value;
        }

        return method_exists($value, 'tryFrom') ? $value->value : null;

    }//end toArrayHelper()


    /**
     * @param mixed[] $value
     * @return mixed[]
     * */
    private static function toArrayHelperArray(array $value): array
    {
        $result = [];

        if (empty($value)) {
            return $result;
        }

        return array_map(fn($item) => self::toArrayHelper($item), $value);

    }//end toArrayHelperArray()


}//end class