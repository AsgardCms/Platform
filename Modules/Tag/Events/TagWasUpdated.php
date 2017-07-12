<?php

namespace Modules\Tag\Events;

use Modules\Tag\Entities\Tag;

class TagWasUpdated
{
    /**
     * @var Tag
     */
    public $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }
}
