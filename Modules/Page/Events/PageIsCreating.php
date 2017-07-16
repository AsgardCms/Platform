<?php

namespace Modules\Page\Events;

use Modules\Core\Events\AbstractEntityHook;
use Modules\Core\Contracts\EntityIsChanging;

class PageIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
