<?php

namespace Modules\Dashboard\Foundation\Widgets;

use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Str;
use Modules\Dashboard\Composers\WidgetViewComposer;

abstract class BaseWidget
{
    /**
     * Boot the widget and add the data to the dashboard view composer
     */
    public function boot()
    {
        $widgetViewComposer = app(WidgetViewComposer::class);
        /** @var Factory $view */
        $view = app(Factory::class);

        if ($view->exists($this->view())) {
            $html = $view->make($this->view())
                         ->with($this->data())
                         ->render();

            $sluggedName = Str::slug($this->name());

            $widgetViewComposer
                ->setWidgetName($sluggedName)
                ->addSubView($sluggedName, $html)
                ->addWidgetOptions($sluggedName, $this->options());
        }
    }

    /**
     * Get the widget name
     * @return string
     */
    abstract protected function name();

    /**
     * Return an array of widget options
     * Possible options:
     *  x, y, width, height
     * @return array
     */
    abstract protected function options();

    /**
     * Get the widget view
     * @return string
     */
    abstract protected function view();

    /**
     * Get the widget data to send to the view
     * @return array
     */
    abstract protected function data();
}
