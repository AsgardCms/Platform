<?php

namespace Modules\Menu\Events;

use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

class MenuIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
