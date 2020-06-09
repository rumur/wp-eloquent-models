<?php

namespace Rumur\WPEloquent\Models\Contracts;

interface WordPressEntitiable {
    /**
     * Represents an instance as WordPress Entity
     *
     * @return mixed
     */
    public function toWordPressEntity();
}