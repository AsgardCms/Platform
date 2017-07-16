<?php

namespace Modules\Core\Events\Handlers;

use League\CommonMark\CommonMarkConverter;
use Modules\Page\Events\ContentIsRendering;

class RenderMarkdown
{
    public function handle(ContentIsRendering $event)
    {
        $converter = new CommonMarkConverter();

        $html = $converter->convertToHtml($event->getOriginal());

        $event->setBody($html);
    }
}
