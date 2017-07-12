<?php

namespace Modules\Tag\Events;

use Modules\Core\Abstracts\AbstractEntityHook;
use Modules\Core\Contracts\EntityIsChanging;

class TagIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
