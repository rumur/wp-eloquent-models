<?php

namespace Rumur\WPModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comment extends Model
{
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
     * @see CommentMeta
     */
    public function meta(): HasMany
    {
        return $this->hasMany(CommentMeta::class, 'comment_id');
    }
}