<?php

namespace Modules\User\Events;

use Modules\Core\Events\AbstractEntityHook;
use Modules\Core\Contracts\EntityIsChanging;

final class UserIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
