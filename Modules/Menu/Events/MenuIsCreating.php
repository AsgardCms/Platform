<?php

namespace Modules\Menu\Events;

use Modules\Core\Events\AbstractEntityHook;
use Modules\Core\Contracts\EntityIsChanging;

class MenuIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
