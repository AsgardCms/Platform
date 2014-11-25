<?php namespace Modules\User\Entities;

use Cartalyst\Sentinel\Users\EloquentUser;
use Laracasts\Presenter\PresentableTrait;

class SentinelUser extends EloquentUser
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
}
