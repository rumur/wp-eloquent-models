<?php

namespace Rumur\WordPress\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Rumur\WordPress\Eloquent\Concerns\HasMeta;
use Rumur\WordPress\Eloquent\Model\Contracts\WordPressEntitiable;

class Comment extends Model implements WordPressEntitiable
{
    use HasMeta;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    public const CREATED_AT = 'comment_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
     */
    protected $dates = [
        'comment_date',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'comment_ID';

    /**
     * Describes relationships between Models and @return HasOne
     *
     * @see Post
     */
    public function post(): HasOne
    {
        return $this->hasOne(Post::class);
    }

    /**
     * Describes relationships between Models and @return HasMany
     *
     * @see Comment\Meta
     */
    public function meta(): HasMany
    {
        return $this->hasMany(Comment\Meta::class, 'comment_id');
    }

    /**
     * Represents an instance as a WordPress entity.
     * 
     * @return null|\WP_Comment
     */
    public function toWordPressEntity(): ?\WP_Comment
    {
        if (!class_exists(\WP_Comment::class)) {
            return null;
        }

        return new \WP_Comment((object)$this->attributesToArray());
    }
}