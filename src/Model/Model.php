<?php

namespace Rumur\WPEloquent\Model;

use Rumur\WPEloquent\Scope\HasGenericMetaAttributesScope;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    use HasGenericMetaAttributesScope;
}