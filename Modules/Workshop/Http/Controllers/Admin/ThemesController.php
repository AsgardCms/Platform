<?php

namespace Modules\Workshop\Http\Controllers\Admin;

use FloatingPoint\Stylist\Theme\Theme;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Workshop\Manager\ThemeManager;

class ThemesController extends AdminBaseController
{
    /**
     * @var ThemeManager
     */
    private $themeManager;

    public function __construct(ThemeManager $themeManager)
    {
        parent::__construct();

        $this->themeManager = $themeManager;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $themes = $this->themeManager->all();

        return view('workshop::admin.themes.index', compact('themes'));
    }

    /**
     * @param Theme $theme
     * @return \Illuminate\View\View
     */
    public function show(Theme $theme)
    {
        return view('workshop::admin.themes.show', compact('theme'));
    }
}
