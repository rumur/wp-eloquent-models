<?php

namespace Rumur\WPEloquent\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Meta extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'meta_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Accessor for `meta_value`.
     *
     * @uses maybe_unserialize
     * @param mixed $value
     * @return mixed
     */
    public function getMetaValueAttribute($value)
    {
        return maybe_unserialize($value);
    }

    /**
     * Mutator for `meta_value`.
     *
     * @uses maybe_serialize
     * @param mixed $value
     * @return mixed
     */
    public function setMetaValueAttribute($value)
    {
        return $this->attributes['meta_value'] = maybe_serialize($value);
    }
}