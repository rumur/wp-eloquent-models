<?php

namespace Rumur\WPEloquent\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Rumur\WPEloquent\Models\Contracts\WordPressEntitiable;

class User extends Model implements WordPressEntitiable
{
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
    protected static $genericMetaData = [
        'description',
        'first_name',
        'last_name',
        'nickname',
        'locale',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'description' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'nickname' => 'string',
        'locale' => 'string',
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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        /**
         * Makes the Model to select columns from a `usermeta` table as well,
         */
        static::addGlobalScope('generic_meta', static function (Builder $builder) {

            if (!empty(static::$genericMetaData)) {
                $table = (new static)->getTable();

                $builder->addSelect([
                    "{$table}.*",
                ]);

                foreach (static::$genericMetaData as $num => $meta_key) {
                    $as = "um{$num}"; // <- alias for a `usermeta` table
                    $builder
                        ->addSelect("{$as}.meta_value as {$meta_key}")
                        ->leftJoin("usermeta as {$as}", static function (JoinClause $join) use ($as, $table, $meta_key) {
                            $join->on("{$as}.user_id", '=', "{$table}.ID")
                                ->where("{$as}.meta_key", $meta_key);
                        });
                }
            }
        });
    }

    /**
     * Describes relationships between Models and @return HasMany
     *
     * @see UserMeta
     */
    public function meta(): HasMany
    {
        return $this->hasMany(UserMeta::class, 'user_id');
    }

    /**
     * Represents an instance as a WordPress \WP_User Entity
     *
     * @return \WP_User
     */
    public function toWordPressEntity(): \WP_User
    {
        return new \WP_User((object)$this->toArray());
    }
}