<?php

namespace Modules\Menu\Events;

use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

class MenuItemIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
