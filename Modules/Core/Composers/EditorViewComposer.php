<?php

namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Modules\Core\Events\EditorIsRendering;
use Modules\Core\Foundation\Asset\Pipeline\AssetPipeline;

class EditorViewComposer
{
    /**
     * @var AssetPipeline
     */
    private $assetPipeline;

    public function __construct(AssetPipeline $assetPipeline)
    {
        $this->assetPipeline = $assetPipeline;
    }

    public function compose(View $view)
    {
        if (app('asgard.onBackend') === false) {
            return;
        }
        event($editor = new EditorIsRendering($this->assetPipeline));
        $view->with('editor', $editor);
    }
}
