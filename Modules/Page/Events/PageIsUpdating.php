<?php

namespace Modules\Page\Events;

use Modules\Core\Contracts\EntityIsChanging;
use Modules\Page\Entities\Page;

class PageIsUpdating implements EntityIsChanging
{
    /**
     * Contains the attributes which can be changed by other listeners
     * @var array
     */
    private $attributes;
    /**
     * Contains the original attributes which cannot be changed
     * @var array
     */
    private $original;
    /**
     * @var Page
     */
    private $page;

    public function __construct(Page $page, array $attributes)
    {
        $this->attributes = $attributes;
        $this->original = $attributes;
        $this->page = $page;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = array_replace_recursive($this->attributes, $attributes);
    }

    /**
     * @return array
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }
}
