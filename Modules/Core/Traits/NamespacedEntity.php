<?php

namespace Modules\Core\Traits;

trait NamespacedEntity
{
    /**
     * Returns the entity namespace.
     */
    public static function getEntityNamespace(): string
    {
        return isset(static::$entityNamespace) ? static::$entityNamespace : get_called_class();
    }

    /**
     * Sets the entity namespace.
     */
    public static function setEntityNamespace(string $namespace)
    {
        static::$entityNamespace = $namespace;
    }
}
