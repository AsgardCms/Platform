<?php

namespace Modules\Page\Events;

class PageWasUpdated
{
    /**
     * @var array
     */
    public $data;
    /**
     * @var int
     */
    public $pageId;

    public function __construct($pageId, array $data)
    {
        $this->data = $data;
        $this->pageId = $pageId;
    }
}
