<?php

namespace Modules\Core\Events\Handlers;

use Modules\Core\Events\EditorIsRendering;

class LoadSimpleMde
{
    public function handle(EditorIsRendering $editor)
    {
        $editor->addJs('simplemde.js')->addCss('simplemde.css');
        $editor->setEditorClass('simplemde');
        $editor->setEditorJsPartial('core::partials.simplemde');

        return false;
    }
}
