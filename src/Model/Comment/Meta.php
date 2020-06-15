<?php

namespace Rumur\WordPress\Eloquent\Model\Comment;

use Rumur\WordPress\Eloquent\Model\Meta as BaseMeta;

class Meta extends BaseMeta
{
    /** @var string  */
    protected $table = 'commentmeta';
}