<?php

namespace Modules\Menu\Services;

class MenuRenderer
{
    /**
     * @var int Id of the menu to render
     */
    protected $menuId;
    /**
     * @var string
     */
    private $startTag = '<div class="dd">';
    /**
     * @var string
     */
    private $endTag = '</div>';
    /**
     * @var string
     */
    private $menu = '';

    /**
     * @param $menuId
     * @param $menuItems
     * @return string
     */
    public function renderForMenu($menuId, $menuItems)
    {
        $this->menuId = $menuId;

        $this->menu .= $this->startTag;
        $this->generateHtmlFor($menuItems);
        $this->menu .= $this->endTag;

        return $this->menu;
    }

    /**
     * Generate the html for the given items
     * @param $items
     */
    private function generateHtmlFor($items)
    {
        $this->menu .= '<ol class="dd-list">';
        foreach ($items as $item) {
            $this->menu .= "<li class=\"dd-item\" data-id=\"{$item->id}\">";
            $editLink = route('dashboard.menuitem.edit', [$this->menuId, $item->id]);
            $style = $item->isRoot() ? 'none' : 'inline';
            $this->menu .= <<<HTML
<div class="btn-group" role="group" aria-label="Action buttons" style="display: {$style}">
    <a class="btn btn-sm btn-info" style="float:left;" href="{$editLink}">
        <i class="fa fa-pencil"></i>
    </a>
    <a class="btn btn-sm btn-danger jsDeleteMenuItem" style="float:left; margin-right: 15px;" data-item-id="{$item->id}">
       <i class="fa fa-times"></i>
    </a>
</div>
HTML;
            $handleClass = $item->isRoot() ? 'dd-handle-root' : 'dd-handle';
            if (isset($item->icon) && $item->icon != '') {
                $this->menu .= "<div class=\"{$handleClass}\"><i class=\"{$item->icon}\" ></i> {$item->title}</div>";
            } else {
                $this->menu .= "<div class=\"{$handleClass}\">{$item->title}</div>";
            }

            if ($this->hasChildren($item)) {
                $this->generateHtmlFor($item->items);
            }

            $this->menu .= '</li>';
        }
        $this->menu .= '</ol>';
    }

    /**
     * @param $item
     * @return bool
     */
    private function hasChildren($item)
    {
        return count($item->items);
    }
}
