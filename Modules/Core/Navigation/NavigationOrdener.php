<?php namespace Modules\Core\Navigation;

use Illuminate\Support\Collection;

class NavigationOrdener
{
    public static function order(Collection $items)
    {
        return $items->sort(
            function ($item1, $item2) {
                $item1 = self::getItem($item1);
                $item2 = self::getItem($item2);

                if ($item1['weight'] > $item2['weight']) {
                    return 1;
                }
                if ($item1['weight'] < $item2['weight']) {
                    return -1;
                }
                return 0;
            }
        );
    }

    /**
     * @param $item
     * @return mixed
     */
    public static function getItem($item)
    {
        return isset($item['weight']) ? $item : $item->first();
    }
}
