<?php

namespace Rumur\WPModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

class Post extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
     */
    protected $dates = [
        'post_date',
        'post_modified',
    ];

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    public const CREATED_AT = 'post_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    public const UPDATED_AT = 'post_modified';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * Describes relationships between models and @return BelongsTo
     *
     * @see User
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'post_author');
    }

    /**
     * Describes relationships between Models and @return HasMany
     *
     * @see Comment
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'comment_post_ID');
    }

    /**
     * Describes relationships between Models and @return HasMany
     *
     * @see PostMeta
     */
    public function meta(): HasMany
    {
        return $this->hasMany(PostMeta::class, 'post_id');
    }

    /**
     * Scope a query by a `post_status`.
     *
     * @param Builder $query
     *
     * @param string $status
     * @return Builder
     */
    public function scopeStatus($query, string $status = 'publish'): Builder
    {
        return $query->where('post_status', $status);
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
        // In order to have multiple taxonomies scopes
        static $num = 0; $num++;

        return $query
            // Explicit Select in order to get rid of results from joined tables
            // @link https://github.com/laravel/framework/issues/4962
            ->select("{$this->table}.*")
            ->join("term_relationships as tr{$num}", function (JoinClause $join) use ($num, $taxonomy) {
                $join->on("{$this->table}.ID", '=', "tr{$num}.object_id")
                    ->join("term_taxonomy as tt{$num}", static function (JoinClause $join) use ($num, $taxonomy) {
                        $join->on("tt{$num}.term_taxonomy_id", '=', "tr{$num}.term_taxonomy_id")
                            ->where("tt{$num}.taxonomy", $taxonomy);
                    });
            });
    }

    /**
     * Scope a query by a `post_type`.
     *
     * @param Builder $query
     *
     * @param string $type
     * @return Builder
     */
    public function scopeType($query, string $type = 'post'): Builder
    {
        return $query->where('post_type', $type);
    }
}