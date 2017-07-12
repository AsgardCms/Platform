<?php

namespace Modules\Media\Events;

use Modules\Core\Abstracts\AbstractEntityHook;
use Modules\Core\Contracts\EntityIsChanging;

final class FileIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
