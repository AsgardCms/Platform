<?php namespace Modules\Menu\Services;

use Illuminate\Support\Facades\URL;

class MenuRenderer
{
    protected $menuId;
    private $startTag = '<div class="dd">';
    private $endTag = '</div>';
    private $menu = '';

    public function renderForMenu($menuId, $menuItems)
    {
        $this->menuId = $menuId;

        $this->menu .= $this->startTag;
        $this->renderItems($menuItems);
        $this->menu .= $this->endTag;

        return $this->menu;
    }

    private function renderItems($items)
    {
        $this->menu .= '<ol class="dd-list">';
        foreach ($items as $item) {
            $this->menu .= "<li class=\"dd-item\" data-id=\"{$item->id}\">";
            $this->menu .= '<a class="btn btn-sm btn-info"
                                   style="float:left; margin-right: 15px;"
                                   href="' . URL::route('dashboard.menuitem.edit', [$this->menuId, $item->id]) . '">
                                   Edit</a>';
            $this->menu .= "<div class=\"dd-handle\">{$item->title}</div>";

            if (!$item->children()->get()->isEmpty()) {
                $this->renderItems($item->children()->get());
            }

            $this->menu .= '</li>';
        }
        $this->menu .= '</ol>';
    }
}
