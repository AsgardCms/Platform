<?php namespace Modules\Menu\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Menu\Repositories\MenuRepository;

class EloquentMenuRepository extends EloquentBaseRepository implements MenuRepository
{
    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($menu, $data)
    {
        $menu->update($data);

        return $menu;
    }
}
