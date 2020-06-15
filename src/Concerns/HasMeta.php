<?php

namespace Rumur\WordPress\Eloquent\Concerns;

use Rumur\WordPress\Eloquent\Utils\Serializer;

trait HasMeta
{
    /**
     * Retrieves a meta filed.
     *
     * @param string $meta_key
     * @param mixed $default
     * @return string
     */
    public function getMeta(string $meta_key, $default = null): string
    {
        $meta_value = null;

        $queried = $this->meta()->where('meta_key', $meta_key)->pluck('meta_value');

        if ($queried->isNotEmpty()) {
            $meta_value = $queried->first()->meta_value; // <- it will be unserialized by an Accessor if needed
        }

        return $meta_value ?? value($default);
    }

    /**
     * Creates/Updates a meta field.
     *
     * @param string $meta_key
     * @param mixed $value
     *
     * @return static
     */
    public function setMeta(string $meta_key, $value)
    {
        $value = Serializer::maybeSerialize($value);

        $data = [
            [
                'meta_key' => $meta_key,
                'meta_value' => $value,
            ],
            [
                'meta_value' => $value,
            ]
        ];

        $this->meta()->updateOrCreate(...$data);

        return $this;
    }

    /**
     * Deletes all meta for this instance with a given `meta_key`.
     *
     * @param string $meta_key
     *
     * @return void
     */
    public function deleteMeta(string $meta_key): void
    {
        $this->meta()->where('meta_key', $meta_key)->delete();
    }
}