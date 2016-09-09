<?php

namespace Modules\Page\Events;

class PageWasCreated
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
