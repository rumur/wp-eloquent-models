<?php

namespace Rumur\WordPress\Eloquent\Scope;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Query\JoinClause;
use Rumur\WordPress\Eloquent\Concerns\HasGenericMetaAttributes;

/**
 * Trait HasPostTypeScope
 *
 * @package Rumur\WordPress\Eloquent\Scope
 */
trait HasGenericMetaAttributesScope
{
    use HasGenericMetaAttributes;

    /**
     * Booted method for trait
     */
    public static function bootHasGenericMetaAttributesScope(): void
    {
        /**
         * Makes the Model to select columns from a `*meta` table as well
         */
        static::addGlobalScope('generic_meta_attributes', static function (Builder $builder) {

            $attributes = static::genericMetaAttributes();

            if (!empty($attributes)) {

                $instance = new static;

                $table = $instance->getTable();

                if (!method_exists($instance, 'meta')) {
                    return;
                }

                $meta = $instance->meta();

                if ($meta instanceof HasOneOrMany === false) {
                    return;
                }

                $foreignKey = $meta->getForeignKeyName();

                $meta_table = $meta->getRelated()->getTable();

                $builder->addSelect([
                    "{$table}.*",
                ]);

                $num = 0;

                foreach ($attributes as $meta_key => $alias) {
                    $num++;
                    $as = "gma{$num}"; // <- alias for a `*meta` table
                    $builder
                        ->addSelect("{$as}.meta_value as {$alias}")
                        ->leftJoin("{$meta_table} as {$as}",
                            static function (JoinClause $join) use ($as, $table, $meta_key, $foreignKey) {
                                $join->on("{$as}.{$foreignKey}", '=', "{$table}.ID")
                                    ->where("{$as}.meta_key", $meta_key);
                            });
                }
            }
        });
    }
}
