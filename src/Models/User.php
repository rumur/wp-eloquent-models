<?php

namespace Rumur\WPModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

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
        return $this->hasMany(UserMeta::class, 'user_id');
    }
}