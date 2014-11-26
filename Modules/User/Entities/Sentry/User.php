<?php namespace Modules\User\Entities\Sentry;

use Cartalyst\Sentry\Users\Eloquent\User as SentryModel;
use Laracasts\Presenter\PresentableTrait;

class User extends SentryModel
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

    public function groups()
    {
        return $this->belongsToMany(static::$groupModel, static::$userGroupsPivot, 'user_id');
    }
}
