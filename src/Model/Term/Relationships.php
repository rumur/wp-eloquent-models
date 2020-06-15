<?php

namespace Rumur\WordPress\Eloquent\Model\Term;

use Illuminate\Database\Eloquent\Model;

class Relationships extends Model
{
    /**
     * This table doesn't have timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $primaryKey = 'term_taxonomy_id';

    /**
     * @var string
     */
    protected $table = 'term_relationships';
}