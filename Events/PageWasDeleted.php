<?php

namespace Modules\Page\Events;

class PageWasDeleted
{
    /**
     * @var object
     */
    public $page;

    public function __construct($page)
    {
        $this->page = $page;
    }
}
