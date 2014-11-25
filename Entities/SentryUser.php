<?php namespace Modules\User\Entities;

use Cartalyst\Sentry\Users\Eloquent\User;
use Laracasts\Presenter\PresentableTrait;

class SentryUser extends User
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
