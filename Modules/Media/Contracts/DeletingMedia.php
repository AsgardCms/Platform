<?php

namespace Modules\Media\Contracts;

interface DeletingMedia
{
    /**
     * Get the entity ID
     * @return int
     */
    public function getEntityId();

    /**
     * Get the class name the imageables
     * @return string
     */
    public function getClassName();
}
