<?php

namespace Modules\User\Events;

use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

class RoleIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
