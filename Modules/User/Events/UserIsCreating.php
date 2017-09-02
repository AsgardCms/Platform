<?php

namespace Modules\User\Events;

use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

final class UserIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
