<?php namespace Modules\User\Entities\Sentinel;

use Cartalyst\Sentinel\Users\EloquentUser;
use Laracasts\Presenter\PresentableTrait;
use Modules\User\Entities\UserInterface;

class User extends EloquentUser implements UserInterface
{
    use PresentableTrait;

    protected $fillable = [
        'email',
        'password',
        'permissions',
        'first_name',
        'last_name'
    ];

    protected $presenter = 'Modules\User\Presenters\UserPresenter';

    public function hasRole($roleId)
    {
        return $this->roles()->whereId($roleId)->count() >= 1;
    }
}
