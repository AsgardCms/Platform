<?php

namespace Modules\User\Events;

use Modules\Core\Contracts\EntityIsChanging;
use Modules\User\Entities\UserInterface;

final class UserIsUpdating implements EntityIsChanging
{
    /**
     * @var UserInterface
     */
    private $user;
    /**
     * @var array
     */
    private $attributes;
    /**
     * @var array
     */
    private $original;

    public function __construct(UserInterface $user, array $data)
    {
        $this->user = $user;
        $this->attributes = $data;
        $this->original = $data;
    }

    /**
     * @return array
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}
