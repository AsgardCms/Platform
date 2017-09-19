<?php

namespace Modules\Page\Events;

use Modules\Page\Entities\Page;

class PageWasUpdated
{
    /**
     * @var array
     */
    public $data;
    /**
     * @var Page
     */
    public $page;

    public function __construct(Page $page, array $data)
    {
        $this->data = $data;
        $this->page = $page;
    }
}
