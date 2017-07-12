<?php

namespace Modules\Menu\Events;

use Modules\Core\Abstracts\AbstractEntityHook;
use Modules\Core\Contracts\EntityIsChanging;

class MenuItemIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
