<?php

namespace Rumur\WordPress\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;
use Rumur\WordPress\Eloquent\Utils\Serializer;

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
     * @param mixed $value
     * @return mixed
     */
    public function getMetaValueAttribute($value)
    {
        return Serializer::maybeUnserialize($value);
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
        return $this->attributes['meta_value'] = Serializer::maybeSerialize($value);
    }
}