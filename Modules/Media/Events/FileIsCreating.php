<?php

namespace Modules\Media\Events;

use Modules\Core\Events\AbstractEntityHook;
use Modules\Core\Contracts\EntityIsChanging;

final class FileIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
