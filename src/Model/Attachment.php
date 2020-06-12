<?php

namespace Rumur\WPEloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Rumur\WPEloquent\Concerns\HasMeta;
use Rumur\WPEloquent\Model\Contracts\WordPressEntitiable;
use Rumur\WPEloquent\Scope\HasPostTypeScope;
use Rumur\WPEloquent\Utils\Serializer;

/**
 * Class Attachment
 * @package Rumur\WPEloquent\Model
 *
 * @property-read mixed metadata
 */
class Attachment extends Model implements WordPressEntitiable
{
    use HasMeta, HasPostTypeScope;

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
     * The `post_type` for an entity.
     *
     * @var string
     */
    protected static $postType = 'attachment';

    /**
     * The meta fields that need to be selected along with main attributes.
     *
     * The key here is a `meta_key` and value is an alias of it,
     * that will be added to attributes.
     *
     * @var array
     */
    protected static $genericMetaAttributesAliased = [
        '_wp_attachment_metadata' => 'metadata',
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
     * @see Attachment\Meta
     */
    public function meta(): HasMany
    {
        return $this->hasMany(Attachment\Meta::class, 'post_id');
    }

    /**
     * Describes relationships between models and @return BelongsTo
     *
     * @see Post
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_parent', 'ID');
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
     * Accessor of `metadata` field.
     *
     * @param $value
     * @return mixed|string
     */
    public function getMetaDataAttribute($value)
    {
        return Serializer::maybeUnserialize($this->attributes['metadata'] ?? $value);
    }
}