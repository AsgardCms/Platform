<?php

namespace Modules\Core\Events\Handlers;

use Modules\Core\Events\EditorIsRendering;

class LoadCkEditor
{
    public function handle(EditorIsRendering $editor)
    {
        $editor->addJs('ckeditor.js');
        $editor->setEditorClass('ckeditor');

        return false;
    }
}
