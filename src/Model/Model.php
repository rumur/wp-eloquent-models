<?php

namespace Rumur\WordPress\Eloquent\Model;

use Rumur\WordPress\Eloquent\Scope\HasGenericMetaAttributesScope;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    use HasGenericMetaAttributesScope;
}