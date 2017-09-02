<?php

namespace Modules\Media\Events;

use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

final class FileIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
