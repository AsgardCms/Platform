<?php namespace Modules\Menu\Repositories\Eloquent;

use Modules\Core\Internationalisation\Helper;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Repositories\MenuRepository;

class EloquentMenuRepository extends EloquentBaseRepository implements MenuRepository
{
    public function create($data)
    {
        $translatableData = Helper::separateLanguages($data);
        dd($translatableData);
        Menu::create($translatableData);
    }
}
