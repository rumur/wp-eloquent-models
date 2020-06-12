<?php

namespace Rumur\WPEloquent\Contracts;

interface WordPressEntitiable {
    /**
     * Represents an instance as WordPress Entity
     *
     * @return mixed
     */
    public function toWordPressEntity();
}