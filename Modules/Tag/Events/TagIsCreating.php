<?php

namespace Modules\Tag\Events;

use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

class TagIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
