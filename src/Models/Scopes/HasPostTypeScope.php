<?php

namespace Rumur\WPEloquent\Models\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
            $builder->where('post_type', '=', static::$postType);
        });

        /**
         * Makes the Model to save it with a specific `post_type`
         */
        static::saving(static function (Model $post) {
            $post->post_type = static::$postType;
        });
    }
}
