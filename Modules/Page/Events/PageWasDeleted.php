<?php

namespace Modules\Page\Events;

use Modules\Page\Entities\Page;

class PageWasDeleted
{
    /**
     * @var Page
     */
    public $page;

    public function __construct($page)
    {
        $this->page = $page;
    }
}
