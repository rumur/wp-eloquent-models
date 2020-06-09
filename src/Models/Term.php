<?php

namespace Rumur\WPEloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Arr;
use Rumur\WPEloquent\Models\Contracts\WordPressEntitiable;

class Term extends Model implements WordPressEntitiable
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'term_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        /**
         * Makes the Model to select columns from a `term_taxonomy` table as well,
         * so it can have the full column set as default \WP_Term
         */
        static::addGlobalScope('term_taxonomy', static function (Builder $builder) {
            $as = 'tt'; // <- alias for a `term_taxonomy` table
            $table = (new static)->getTable();
            $builder->addSelect([
                "{$table}.*",
                "{$as}.count as count",
                "{$as}.parent as parent",
                "{$as}.taxonomy as taxonomy",
                "{$as}.description as description",
                "{$as}.term_taxonomy_id as term_taxonomy_id",
            ])->join("term_taxonomy as {$as}", "{$as}.term_id", '=', "{$table}.term_id");
        });
    }

    /**
     * Describes relationships between Models and @return HasMany
     *
     * @see TermMeta
     */
    public function meta(): HasMany
    {
        return $this->hasMany(TermMeta::class, 'term_id');
    }

    /**
     * Represents an instance as a \WP_Term WordPress Entity
     *
     * @return \WP_Term
     */
    public function toWordPressEntity(): \WP_Term
    {
        return new \WP_Term((object)Arr::except($this->toArray(), [
            'meta',
        ]));
    }

    /**
     * Scope a query by a `taxonomy`.
     *
     * @param Builder $query
     *
     * @param string $taxonomy
     * @return Builder
     */
    public function scopeTaxonomy(Builder $query, string $taxonomy = 'category'): Builder
    {
        $as = 'tt'; // <- alias for a `term_taxonomy` table

        // Need to double check if a global `term_taxonomy` scope is still available
        if (static::hasGlobalScope('term_taxonomy')
            && !in_array('term_taxonomy', $query->removedScopes(), true)) {
            return $query->where("{$as}.taxonomy", $taxonomy);
        }

        // Otherwise we need to join the table once again.
        return $query->join("term_taxonomy as {$as}", function (JoinClause $join) use ($as, $taxonomy) {
            $join->on("{$as}.term_id", '=', "{$this->getTable()}.term_id")
                ->where("{$as}.taxonomy", $taxonomy);
        });
    }
}