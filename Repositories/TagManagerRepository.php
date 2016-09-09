<?php

namespace Modules\Tag\Repositories;

use Modules\Tag\Contracts\TaggableInterface;

class TagManagerRepository implements TagManager
{
    /**
     * Array of registered namespaces.
     * @var array
     */
    private $namespaces = [];

    /**
     * Returns all the registered namespaces.
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * Registers an entity namespace.
     * @param TaggableInterface $entity
     * @return void
     */
    public function registerNamespace(TaggableInterface $entity)
    {
        $this->namespaces[] = $entity->getEntityNamespace();
    }
}
