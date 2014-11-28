<?php namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Modules\Core\Foundation\Theme\ThemeManager;

class ThemeComposer
{
    /**
     * @var ThemeManager
     */
    private $themeManager;

    public function __construct()
    {
        $this->themeManager = app('asgard.themes');
    }

    public function compose(View $view)
    {
        $view->with('themes', $this->themeManager->all());
    }
}
