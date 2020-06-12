<?php

namespace Rumur\WPEloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Rumur\WPEloquent\Concerns\HasMeta;
use Rumur\WPEloquent\Model\Contracts\WordPressEntitiable;

/**
 * Class User
 *
 * @property    int ID
 * @property string user_pass
 * @property string user_url
 * @property string user_login
 * @property string user_email
 * @property string user_nicename
 * @property string display_name
 * @property Carbon user_registered
 * @property string user_activation_key
 * @property    int user_status
 * @property string description
 * @property string first_name
 * @property string last_name
 * @property string nickname
 * @property string locale
 *
 * @package Rumur\WPEloquent\Model
 */
class User extends Model implements WordPressEntitiable
{
    use HasMeta;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * The attributes that should be loaded along from `usermeta` table.
     *
     * @var string[]
     */
    protected static $genericMetaAttributes = [
        'description',
        'first_name',
        'last_name',
        'nickname',
        'locale',
    ];

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    public const CREATED_AT = 'user_registered';

    /**
     * The name of the "updated at" column.
     */
    public const UPDATED_AT = null;

    /**
     * Describes relationships between Models and @return HasMany
     *
     * @see UserMeta
     */
    public function meta(): HasMany
    {
        return $this->hasMany(User\Meta::class, 'user_id');
    }

    /**
     * Represents an instance as a WordPress \WP_User Entity
     *
     * @return null|\WP_User
     */
    public function toWordPressEntity(): ?\WP_User
    {
        if (!class_exists(\WP_User::class)) {
            return null;
        }

        return new \WP_User((object)$this->attributesToArray());
    }
}