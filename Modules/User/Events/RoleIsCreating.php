<?php

namespace Modules\User\Events;

use Modules\Core\Events\AbstractEntityHook;
use Modules\Core\Contracts\EntityIsChanging;

class RoleIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
