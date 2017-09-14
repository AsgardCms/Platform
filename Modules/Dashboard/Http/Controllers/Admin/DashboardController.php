<?php

namespace Modules\Dashboard\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Dashboard\Repositories\WidgetRepository;
use Modules\User\Contracts\Authentication;
use Nwidart\Modules\Contracts\RepositoryInterface;

class DashboardController extends AdminBaseController
{
    /**
     * @var WidgetRepository
     */
    private $widget;
    /**
     * @var Authentication
     */
    private $auth;

    /**
     * @param RepositoryInterface $modules
     * @param WidgetRepository $widget
     * @param Authentication $auth
     */
    public function __construct(RepositoryInterface $modules, WidgetRepository $widget, Authentication $auth)
    {
        parent::__construct();
        $this->bootWidgets($modules);
        $this->widget = $widget;
        $this->auth = $auth;
    }

    /**
     * Display the dashboard with its widgets
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->requireAssets();

        $widget = $this->widget->findForUser($this->auth->id());

        $customWidgets = json_encode(null);
        if ($widget) {
            $customWidgets = $widget->widgets;
        }

        return view('dashboard::admin.dashboard', compact('customWidgets'));
    }

    /**
     * Save the current state of the widgets
     * @param Request $request
     * @return mixed
     */
    public function save(Request $request)
    {
        $widgets = $request->get('grid');

        if (empty($widgets)) {
            return Response::json([false]);
        }

        $this->widget->updateOrCreateForUser($widgets, $this->auth->id());

        return Response::json([true]);
    }

    /**
     * Reset the grid for the current user
     */
    public function reset()
    {
        $widget = $this->widget->findForUser($this->auth->id());

        if (!$widget) {
            return redirect()->route('dashboard.index')->with('warning', trans('dashboard::dashboard.reset not needed'));
        }

        $this->widget->destroy($widget);

        return redirect()->route('dashboard.index')->with('success', trans('dashboard::dashboard.dashboard reset'));
    }

    /**
     * Boot widgets for all enabled modules
     * @param RepositoryInterface $modules
     */
    private function bootWidgets(RepositoryInterface $modules)
    {
        foreach ($modules->enabled() as $module) {
            if (! $module->widgets) {
                continue;
            }
            foreach ($module->widgets as $widgetClass) {
                app($widgetClass)->boot();
            }
        }
    }

    /**
     * Require necessary assets
     */
    private function requireAssets()
    {
        $this->assetPipeline->requireJs('lodash.js');
        $this->assetPipeline->requireJs('jquery-ui-core.js');
        $this->assetPipeline->requireJs('jquery-ui-widget.js');
        $this->assetPipeline->requireJs('jquery-ui-mouse.js');
        $this->assetPipeline->requireJs('jquery-ui-draggable.js');
        $this->assetPipeline->requireJs('jquery-ui-resizable.js');
        $this->assetPipeline->requireJs('gridstack.js');
        $this->assetPipeline->requireJs('chart.js');
        $this->assetPipeline->requireCss('gridstack.css')->before('asgard.css');
    }
}
