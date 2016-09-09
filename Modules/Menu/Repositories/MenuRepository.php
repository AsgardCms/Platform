<?php

namespace Modules\Menu\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface MenuRepository extends BaseRepository
{
    /**
     * Get all online menus
     * @return object
     */
    public function allOnline();
}
