<?php

namespace Modules\User\Events;

use Modules\Core\Abstracts\AbstractEntityHook;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\User\Entities\UserInterface;

final class UserIsUpdating extends AbstractEntityHook implements EntityIsChanging
{
    /**
     * @var UserInterface
     */
    private $user;

    public function __construct(UserInterface $user, array $data)
    {
        $this->user = $user;
        parent::__construct($data);
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}
