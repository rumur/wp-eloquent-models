<?php

namespace Rumur\WordPress\Eloquent\Model\Attachment;

use Rumur\WordPress\Eloquent\Model\Meta as BaseMeta;

class Meta extends BaseMeta
{
    /** @var string  */
    protected $table = 'postmeta';
}