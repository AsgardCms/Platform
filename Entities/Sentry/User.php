<?php namespace Modules\User\Entities\Sentry;

use Cartalyst\Sentry\Users\Eloquent\User as SentryModel;
use Laracasts\Presenter\PresentableTrait;
use Modules\User\Entities\UserInterface;

class User extends SentryModel implements UserInterface
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

    /**
     * Checks if a user belongs to the given Role ID
     * @param int $roleId
     * @return bool
     */
    public function hasRole($roleId)
    {
    }
}
