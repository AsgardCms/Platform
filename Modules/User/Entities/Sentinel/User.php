<?php

namespace Modules\User\Entities\Sentinel;

use Illuminate\Auth\Authenticatable;
use Modules\User\Entities\UserToken;
use Modules\User\Entities\UserInterface;
use Laracasts\Presenter\PresentableTrait;
use Cartalyst\Sentinel\Users\EloquentUser;
use Modules\User\Presenters\UserPresenter;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Modules\Core\Doctrine\HasDynamicRelationships;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends EloquentUser implements UserInterface, AuthenticatableContract
{
    use PresentableTrait, HasDynamicRelationships, Authenticatable;

    protected $fillable = [
        'email',
        'password',
        'permissions',
        'first_name',
        'last_name',
    ];

    /**
     * {@inheritDoc}
     */
    protected $loginNames = ['email'];

    protected $presenter = UserPresenter::class;

    public function __construct(array $attributes = [])
    {
        $this->loginNames = config('asgard.user.config.login-columns');
        $this->fillable = config('asgard.user.config.fillable');
        if (config()->has('asgard.user.config.presenter')) {
            $this->presenter = config('asgard.user.config.presenter', UserPresenter::class);
        }
        if (config()->has('asgard.user.config.dates')) {
            $this->dates = config('asgard.user.config.dates', []);
        }
        if (config()->has('asgard.user.config.casts')) {
            $this->casts = config('asgard.user.config.casts', []);
        }

        parent::__construct($attributes);
    }

    /**
     * @inheritdoc
     */
    public function hasRoleId($roleId)
    {
        return $this->roles()->whereId($roleId)->count() >= 1;
    }

    /**
     * @inheritdoc
     */
    public function hasRoleSlug($slug)
    {
        return $this->roles()->whereSlug($slug)->count() >= 1;
    }

    /**
     * @inheritdoc
     */
    public function hasRoleName($name)
    {
        return $this->roles()->whereName($name)->count() >= 1;
    }

    /**
     * @inheritdoc
     */
    public function isActivated()
    {
        if (Activation::completed($this)) {
            return true;
        }

        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function api_keys()
    {
        return $this->hasMany(UserToken::class);
    }

    /**
     * @inheritdoc
     */
    public function getFirstApiKey()
    {
        $userToken = $this->api_keys->first();

        if ($userToken === null) {
            return '';
        }

        return $userToken->access_token;
    }

    /**
     * @inheritdoc
     */
    public function hasAccess($permission)
    {
        $permissions = $this->getPermissionsInstance();

        return $permissions->hasAccess($permission);
    }
}
