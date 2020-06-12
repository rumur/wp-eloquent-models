<?php

namespace Rumur\WPEloquent\Scope;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasPostTypeScope
 *
 * @package Rumur\WPEloquent\Scope
 */
trait HasPostTypeScope
{
    /**
     * Booted method for trait
     */
    public static function bootHasPostTypeScope(): void
    {
        /**
         * Makes the Model to load with a specific `post_type`
         */
        static::addGlobalScope('post_type', static function(Builder $builder) {
            $builder->where('post_type', '=', static::postType());
        });

        /**
         * Makes the Model to save save it with a specific `post_type`
         */
        static::saving(static function (Model $post) {
            $post->post_type = static::postType();
        });
    }

    /**
     * @return string
     */
    public static function postType(): string
    {
        return static::$postType ?? strtolower(class_basename(static::class));
    }
}
