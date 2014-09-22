<?php namespace Modules\Core\Composers;

class SidebarViewComposer
{
    public function compose($view)
    {
        $view->items->sort(function($item1, $item2) {
            if (is_object($item1)) {
                if ($item1->first()['weight'] > $item2['weight']) {
                    return 1;
                }
                if ($item1->first()['weight'] < $item2['weight']) {
                    return -1;
                }
                return 0;
            }
            if (is_object($item2)) {
                if ($item1['weight'] > $item2->first()['weight']) {
                    return 1;
                }
                if ($item1['weight'] < $item2->first()['weight']) {
                    return -1;
                }
                return 0;
            }
            if ($item1['weight'] > $item2['weight']) {
                return 1;
            }
            if ($item1['weight'] < $item2['weight']) {
                return -1;
            }
            return 0;
        });
    }
}