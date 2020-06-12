<?php

namespace Rumur\WPEloquent\Model\Post;

use Rumur\WPEloquent\Model\Meta as BaseMeta;

class Meta extends BaseMeta
{
    /** @var string  */
    protected $table = 'postmeta';
}