<?php

namespace Rumur\WordPress\Eloquent\Model\Contracts;

interface WordPressEntitiable {
    /**
     * Represents an instance as WordPress Entity
     *
     * @return mixed
     */
    public function toWordPressEntity();
}