<?php

namespace Modules\Core\Events\Handlers;

use League\CommonMark\CommonMarkConverter;
use Modules\Page\Events\PageContentIsRendering;

class RenderMarkdown
{
    public function handle(PageContentIsRendering $event)
    {
        $converter = new CommonMarkConverter();

        $html = $converter->convertToHtml($event->getOriginal());

        $event->setBody($html);
    }
}
