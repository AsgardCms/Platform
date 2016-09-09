<?php

namespace Modules\Dashboard\Composers;

use Illuminate\Contracts\View\View;

class WidgetViewComposer
{
    /**
     * @var array
     */
    private $subViews = [];

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with(['widgets' => $this->subViews]);
    }

    /**
     * Add the html of the widget view to the given widget name
     * @param string $name
     * @param string $view
     * @return $this
     */
    public function addSubview($name, $view)
    {
        $this->subViews[$name]['html'] = $view;

        return $this;
    }

    /**
     * Add widget options to the given widget name
     * @param $name
     * @param array $options
     * @return $this
     */
    public function addWidgetOptions($name, array $options)
    {
        $this->subViews[$name]['options'] = $options;

        return $this;
    }

    /**
     * Set the widget name
     * @param string $name
     * @return $this
     */
    public function setWidgetName($name)
    {
        $this->subViews[$name]['id'] = $name;

        return $this;
    }
}
