<?php

namespace Code16\ContentRenderer\Support;

use Illuminate\Support\Collection;
use Illuminate\View\ComponentAttributeBag;
use Stringable;

class ComponentAttributeBagCollection
{
    protected Collection $collection;

    public function __construct()
    {
        $this->collection = new Collection();
    }

    public function get(string $componentName): ?ComponentAttributeBag
    {
        return $this->collection->get($componentName);
    }

    public function put(string $componentName, ComponentAttributeBag|array $attributes): void
    {
        if (is_array($attributes)) {
            $attributes = new ComponentAttributeBag($attributes);
        }

        $this->collection[$componentName] = $this->mergedAttributes($componentName, $attributes);
    }

    protected function mergedAttributes(string $componentName, ComponentAttributeBag $attributes): ComponentAttributeBag
    {
        if ($existingAttributes = $this->get($componentName)) {
            return static::mergeAttributes($attributes, $existingAttributes->getAttributes());
        }

        return $attributes;
    }

    /**
     * Merge default attributes into the bag, escaping only values that can be
     * rendered as strings: closures and other objects are passed as-is.
     */
    public static function mergeAttributes(ComponentAttributeBag $attributes, array $attributeDefaults): ComponentAttributeBag
    {
        [$rawDefaults, $escapableDefaults] = (new Collection($attributeDefaults))
            ->partition(fn ($value) => is_object($value) && ! $value instanceof Stringable);

        return $attributes
            ->merge($escapableDefaults->all())
            ->merge($rawDefaults->all(), escape: false);
    }
}
