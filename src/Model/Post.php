<?php

namespace Rumur\WordPress\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\JoinClause;
use Rumur\WordPress\Eloquent\Concerns\HasMeta;
use Rumur\WordPress\Eloquent\Model\Contracts\WordPressEntitiable;

/**
 * Class Post
 * @package Rumur\WordPress\Eloquent\Model
 *
 * @method self status(string $status)
 * @method self type(string $post_type)
 * @method self taxonomy(string $taxonomy)
 */
class Post extends Model implements WordPressEntitiable
{
    use HasMeta;

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
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'laravel_through_key',
    ];

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
     * @see Attachment
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class, 'post_parent', 'ID');
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
     * @see Post\Meta
     */
    public function meta(): HasMany
    {
        return $this->hasMany(Post\Meta::class, 'post_id');
    }

    /**
     * Describes relationships between Model and @return HasManyThrough
     *
     * @see Term::class
     */
    public function terms(): HasManyThrough
    {
        return $this->hasManyThrough(Term\Taxonomy::class, Term\Relationships::class,
            'object_id', 'term_id')->with('term');
    }

    /**
     * Represents an instance as a \WP_Post WordPress Entity
     *
     * @return null|\WP_Post
     */
    public function toWordPressEntity(): ?\WP_Post
    {
        if (!class_exists(\WP_Post::class)) {
            return null;
        }

        return new \WP_Post((object)$this->attributesToArray());
    }

    /**
     * Scope a query by a `post_status`.
     *
     * @param Builder $query
     *
     * @param string $status
     * @return Builder
     */
    public function scopeStatus($query, string $status): Builder
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
            ->select("{$this->getTable()}.*")
            ->join("term_relationships as tr{$num}", function (JoinClause $join) use ($num, $taxonomy) {
                $join->on("{$this->getTable()}.ID", '=', "tr{$num}.object_id")
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
        // If there is a global scope already we return as it is.
        if (static::hasGlobalScope('post_type')
            && !in_array('post_type', $query->removedScopes(), true)) {
            return $query;
        }

        return $query->where('post_type', $type);
    }
}