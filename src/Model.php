<?php

declare(strict_types=1);

namespace CastModels;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;
use ReflectionProperty;
use JsonSerializable;
use stdClass;

abstract class Model implements JsonSerializable, CastsAttributes
{
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
            if (! empty($item)) {
                $collection->add(new static($item));
            }
        }

        return $collection;
    }

    public function __construct(array|stdClass $data = [])
    {
        $this->update($data);
    }

    public function update(array|stdClass $data = [])
    {
        if (empty($data)) {
            return;
        }

        foreach ($data as $k => $v) {
            if (isset($v) && property_exists($this, $k)) {
                $this->setProperty($k, $v);
            }
        }
    }

    public function toArray(): array
    {
        $result = [];

        foreach (get_object_vars($this) as $property => $value) {
            $result[$property] = $this->toArrayHelper($value);
        }

        return $result;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function __toString()
    {
        return json_encode($this);
    }

    /**
     * Cast the given value.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array|static|Collection
     */
    public function get($model, $key, $value, $attributes)
    {
        if (empty($value) || $value == 'null') {
            return null;
        }

        $newValue = json_decode($value);

        if (is_object($newValue)) {
            return new static($newValue);
        }

        return static::collection($newValue);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param array $value
     * @param array $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        if (empty($value) || $value == 'null') {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        return json_encode($value);
    }

    private function setProperty(string $propertyName, mixed $value)
    {
        $rProperty = new ReflectionProperty($this, $propertyName);
        $rType = $rProperty->getType();

        if ($rType->isBuiltin()) {
            $this->$propertyName = is_object($value) ? (array) $value : $value;
        } else {
            $type = $rType->getName();

            if (method_exists($type, 'tryFrom')) {
                $this->$propertyName = $type::tryFrom($value);
                return;
            }

            $type = new $type();

            if ($type instanceof Collection) {
                $this->setCollection($rProperty, $propertyName, $value);
                return;
            }

            if ($type instanceof Model) {
                $this->$propertyName = new $type($value);
                return;
            }
        }
    }

    private function setCollection(ReflectionProperty $rProperty, string $propertyName, mixed $value)
    {
        if (empty($value)) {
            return;
        }

        $phpDoc = $rProperty->getDocComment();

        if (! $phpDoc) {
            $this->$propertyName = collect([$value]);
            return;
        }

        $className = $this->getClassNameFromPhpDoc($phpDoc);

        if (! strlen($className)) {
            $this->$propertyName = $value;
            return;
        }

        if (method_exists($className, 'tryFrom')) {
            $this->$propertyName = collect(array_map(fn ($v) => $className::tryFrom($v), $value));
            return;
        };

        $array = [];
        foreach ($value as $v) {
            if (! empty($v)) {
                $array[] = new $className($v);
            }
        }

        if (! empty($array)) {
            $this->$propertyName = collect($array);
        }
    }

    private function getClassNameFromPhpDoc(string $phpDoc): string
    {
        $pattern = '/\s+([\w|\\\\]+)\s*\*/m';
        $matches = [];

        preg_match($pattern, $phpDoc, $matches, PREG_OFFSET_CAPTURE);

        return $matches[1][0];
    }

    private function toArrayHelper(mixed $value): mixed
    {
        if (! is_object($value)) {
            return $value;
        }

        if ($value instanceof Model) {
            return $value->toArray();
        }

        if ($value instanceof Collection) {
            return $this->toArrayHelperCollection($value);
        }

        if (method_exists($value, 'tryFrom')) {
            return $value->value;
        }
    }

    private function toArrayHelperCollection(Collection $value): array
    {
        if (! $value->count()) {
            return null;
        }

        $result = [];

        $value->each(
            function ($item) use (&$result) {
                $result[] = $this->toArrayHelper($item);
            }
        );

        return $result;
    }
}
