<?php

namespace Modules\User\Events;

use Modules\Core\Abstracts\AbstractEntityHook;
use Modules\Core\Contracts\EntityIsChanging;

class RoleIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
