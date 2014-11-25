<?php namespace Modules\User\Repositories\Sentry;

use Modules\Core\Contracts\Authentication;
use Cartalyst\Sentry\Facades\Laravel\Sentry;

class SentryAuthentication implements Authentication
{
    /**
     * Authenticate a user
     * @param array $credentials
     * @param bool $remember Remember the user
     * @return mixed
     */
    public function login(array $credentials, $remember = false)
    {
        if (Sentry::authenticate($credentials, $remember)) {
            return false;
        }
        return 'Invalid login or password.';
    }

    /**
     * Register a new user.
     * @param array $user
     * @return bool
     */
    public function register(array $user)
    {
        return Sentry::register($user);
    }

    /**
     * Activate the given used id
     * @param int $userId
     * @param string $code
     * @return mixed
     */
    public function activate($userId, $code)
    {
        $user = Sentry::findUserById($userId);

        return $user->attemptActivation($code);
    }

    /**
     * Assign a role to the given user.
     * @param \Modules\User\Repositories\UserRepository $user
     * @param \Modules\User\Repositories\RoleRepository $role
     * @return mixed
     */
    public function assignRole($user, $role)
    {
        return $role->users()->attach($user);
    }

    /**
     * Log the user out of the application.
     * @return mixed
     */
    public function logout()
    {
        return Sentry::logout();
    }

    /**
     * Create an activation code for the given user
     * @param $user
     * @return mixed
     */
    public function createActivation($user)
    {
        return $user->getResetPasswordCode();
    }

    /**
     * Create a reminders code for the given user
     * @param $user
     * @return mixed
     */
    public function createReminderCode($user)
    {
        return $user->getResetPasswordCode();
    }

    /**
     * Completes the reset password process
     * @param $user
     * @param string $code
     * @param string $password
     * @return bool
     */
    public function completeResetPassword($user, $code, $password)
    {
        return $user->attemptResetPassword($code, $password);
    }

    /**
     * Determines if the current user has access to given permission
     * @param $permission
     * @return bool
     */
    public function hasAccess($permission)
    {
        return Sentry::hasAccess($permission);
    }

    /**
     * Check if the user is logged in
     * @return mixed
     */
    public function check()
    {
        return Sentry::check();
    }
}
