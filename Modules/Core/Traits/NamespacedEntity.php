<?php

namespace Modules\Core\Traits;

trait NamespacedEntity
{
    /**
     * Returns the entity namespace.
     *
     * @return string
     */
    public static function getEntityNamespace()
    {
        return isset(static::$entityNamespace) ? static::$entityNamespace : get_called_class();
    }

    /**
     * Sets the entity namespace.
     *
     * @param  string  $namespace
     * @return void
     */
    public static function setEntityNamespace($namespace)
    {
        static::$entityNamespace = $namespace;
    }
}
