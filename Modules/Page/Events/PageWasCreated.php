<?php

namespace Modules\Page\Events;

use Modules\Media\Contracts\StoringMedia;
use Modules\Page\Entities\Page;

class PageWasCreated implements StoringMedia
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

    /**
     * Return the entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getEntity()
    {
        return $this->page;
    }

    /**
     * Return the ALL data sent
     * @return array
     */
    public function getSubmissionData()
    {
        return $this->data;
    }
}
