<?php

declare(strict_types=1);

namespace CastModels;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use ReflectionNamedType;
use ReflectionUnionType;
use ReflectionProperty;
use JsonSerializable;
use stdClass;

abstract class Model implements JsonSerializable, Arrayable
{


    /**
     * @param array<array<string, mixed>>|string $data
     * @return Collection<int, static>
     * */
    public static function collection(array|string $data): Collection
    {
        $collection = collect();

        if (empty($data)) {
            return $collection;
        }

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        foreach ($data as $item) {
            if (!empty($item)) {
                // @phpstan-ignore-next-line
                $collection->add(new static($item));
            }
        }

        return $collection;
    }//end collection()


    /** @param array<string, mixed>|stdClass $data */
    public function __construct(array|stdClass $data = [])
    {
        $this->update($data);

    }//end __construct()


    /** @param array<string, mixed>|stdClass $data */
    public function update(array|stdClass $data = []): void
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

    /**
     * Cast the given value.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @param string $key
     * @param mixed $value
     * @param array<string, mixed>  $attributes
     * @return array|static|Collection|null
     */
    public function get($model, $key, $value, $attributes): mixed
    {
        if (empty($value) || $value == 'null') {
            return null;
        }

        if (is_object($value)) {
            return $value;
        }

        $newValue = json_decode($value);

        if (is_object($newValue)) {
            // @phpstan-ignore-next-line
            return new static($newValue);
        }

        return static::collection($newValue);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array<string, mixed>  $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes): mixed
    {
        if (empty($value) || $value == 'null') {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return $value->__toString();
    }


    private static function setProperty(
        Model $instance,
        string $propertyName,
        mixed $value
    ): void {
        $rProperty = new ReflectionProperty($instance, $propertyName);
        $rType = $rProperty->getType();

        if (empty($rType)) {
            return;
        }

        $rType instanceof ReflectionNamedType ? 
            self::setPropertyNamedType(
            $instance,
                $rProperty,
                $rType,
                $propertyName,
                $value
            )
            : self::setPropertyUnionType(
            $instance,
                $rProperty,
                $rType,
                $propertyName,
                $value
            );

    }//end setProperty()

    private static function setPropertyNamedType(
        Model $instance,
        ReflectionProperty $rProperty,
        ReflectionNamedType $rType,
        string $propertyName,
        mixed $value
    ): void {
        if ($rType->isBuiltin()) {
            self::setPropertyBuiltIn($instance, $propertyName, $value);
            return;
        }

        $className = $rType->getName();

        if ($className === Collection::class) {
            self::setCollection($instance, $rProperty, $propertyName, $value);
            return;
        }

        self::setPropertyHelper($instance, $className, $propertyName, $value);
    }//end setPropertyNamedType()

    private static function setPropertyUnionType(
        Model $instance,
        ReflectionProperty $rProperty,
        ReflectionUnionType $rType,
        string $propertyName,
        mixed $value
    ): void {
        $types = $rType->getTypes();
        $max = count($types);
        $i = 0;
        $dataStructure = [];
        $valueStructure = array_keys($value);

        do
        {
            self::setUnionTypeDifferences(
                $types[$i],
                $rProperty,
                $value,
                $dataStructure,
                $valueStructure
            );
        } while (++$i < $max);

        if (empty($dataStructure['type'])) {
            return;
        }

        self::setPropertyNamedType(
            $instance,
            $rProperty,
            $dataStructure['type'],
            $propertyName,
            $value
        );

    }//end setPropertyUnionType()

    private static function setUnionTypeDifferences(
        ReflectionNamedType $type,
        ReflectionProperty $rProperty,
        mixed $value,
        array &$dataStructure,
        array $valueStructure
    ): void {
        $className = $type->getName();
        $isCollection = $className === Collection::class;

        if (!class_exists($className)
            || ($isCollection && !is_numeric(array_key_first($value)))) {
            return;
        }

        if ($isCollection) {
            $className2 = self::getCollectionClassName($rProperty);

            if (!class_exists($className2)) {
                return;
            }

            $dataStructure['type'] = $type;
            $dataStructure['diff'] = 0;
            return;
        }

        $classStructure = array_keys(get_class_vars($className));
        $diff = count(array_diff($classStructure, $valueStructure));

        if (empty($dataStructure['type'])
            || $dataStructure['diff'] > $diff) {
            $dataStructure['type'] = $type;
            $dataStructure['diff'] = $diff;
        }

    }//end getUnionTypeDifferences()


    private static function setPropertyBuiltIn(
        Model $instance,
        string $propertyName,
        mixed $value
    ): void {
        if (is_object($value)) {
            $value = (array) $value;
        }

        $instance->$propertyName = $value;

    }//end setPropertyBuiltIn()


    private static function setCollection(
        Model $instance,
        ReflectionProperty $rProperty,
        string $propertyName,
        mixed $value
    ): void {
        if (empty($value)) {
            return;
        }

        $className = self::getCollectionClassName($rProperty);

        if (empty($className)) {
            $instance->$propertyName = $value;
            return;
        }

        $value = (method_exists($className, 'tryFrom')) ?
        array_map(fn($v) => $className::tryFrom($v), $value)
        : array_map(fn($v) => new $className($v), $value);

        $instance->$propertyName = collect($value);

    }//end setArray()

    private static function getCollectionClassName(
        ReflectionProperty $rProperty
    ): string|false {
        $phpDoc = $rProperty->getDocComment();

        if (!$phpDoc) {
            return false;
        }

        return self::getClassNameFromPhpDoc($phpDoc);

    }//end getCollectionClassName()


    private static function getClassNameFromPhpDoc(
        string $phpDoc
    ): string|false {
        $pattern = '/<\s*(\\\\?[A-Za-z_\\\\][A-Za-z0-9_\\\\]*)\s*>/m';
        $matches = [];

        preg_match($pattern, $phpDoc, $matches, PREG_OFFSET_CAPTURE);

        return empty($matches) ? false : $matches[1][0];

    }//end getClassNameFromPhpDoc()


    private static function setPropertyHelper(
        Model $instance,
        string $type,
        string $propertyName,
        mixed $value
    ): void {
        if (method_exists($type, 'tryFrom')) {
            $instance->$propertyName = $type::tryFrom($value);
            return;
        }

        $type = new $type();

        if ($type instanceof Model) {
            $instance->$propertyName = new $type($value);
        }

        $instance->$propertyName = $value;

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

        if ($value instanceof Arrayable) {
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

        return array_map(fn($item) =>
            self::toArrayHelper($item), $value);
    }//end toArrayHelperArray()

    /**
     * Get the serialized representation of the value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function serialize(
        EloquentModel $model,
        string $key,
        mixed $value,
        array $attributes,
    ): mixed {
        return $value->toArray();
    }

}//end class
