<?php

namespace Modules\Media\Events;

use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

class FolderIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
