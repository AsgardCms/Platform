<?php

namespace Modules\User\Events;

use Modules\Core\Abstracts\AbstractEntityHook;
use Modules\Core\Contracts\EntityIsChanging;

final class UserIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
