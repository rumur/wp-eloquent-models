<?php

namespace Rumur\WordPress\Eloquent\Concerns;

trait HasGenericMetaAttributes
{
    /**
     * The meta fields that need to be selected along with main attributes.
     *
     * Example:
     *  [
     *      '_wp_attachment_metadata',
     *      // ...
     *  ]
     *
     * @var string[]
     */
    protected static $genericMetaAttributes = [];

    /**
     * The meta fields that need to be selected along with main attributes.
     *
     * The key here is a `meta_key` and value is an alias of it,
     * that will be added to attributes.
     *
     * Example:
     *  [
     *      '_wp_attachment_metadata' => 'metadata',
     *      // ...
     *  ]
     *
     * @var array
     */
    protected static $genericMetaAttributesAliased = [];

    /**
     * Gets meta attributes with their aliases.
     *
     * @return array
     */
    public static function genericMetaAttributes(): array
    {
        return array_merge(
            array_combine(static::$genericMetaAttributes, static::$genericMetaAttributes),
            static::$genericMetaAttributesAliased
        );
    }
}