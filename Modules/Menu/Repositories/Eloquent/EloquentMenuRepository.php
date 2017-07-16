<?php

namespace Modules\Menu\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Menu\Events\MenuIsCreating;
use Modules\Menu\Events\MenuIsUpdating;
use Modules\Menu\Events\MenuWasCreated;
use Modules\Menu\Events\MenuWasUpdated;
use Modules\Menu\Repositories\MenuRepository;

class EloquentMenuRepository extends EloquentBaseRepository implements MenuRepository
{
    public function create($data)
    {
        event($event = new MenuIsCreating($data));
        $menu = $this->model->create($event->getAttributes());

        event(new MenuWasCreated($menu));

        return $menu;
    }

    public function update($menu, $data)
    {
        event($event = new MenuIsUpdating($menu, $data));
        $menu->update($event->getAttributes());

        event(new MenuWasUpdated($menu));

        return $menu;
    }

    /**
     * Get all online menus
     * @return object
     */
    public function allOnline()
    {
        $locale = App::getLocale();

        return $this->model->whereHas('translations', function (Builder $q) use ($locale) {
            $q->where('locale', "$locale");
            $q->where('status', 1);
        })->with('translations')->orderBy('created_at', 'DESC')->get();
    }
}
