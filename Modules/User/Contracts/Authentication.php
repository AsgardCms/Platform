<?php

namespace Modules\User\Contracts;

use Cartalyst\Sentinel\Users\UserInterface;

interface Authentication
{
    /**
     * Authenticate a user
     * @param  array $credentials
     * @param  bool  $remember    Remember the user
     * @return mixed
     */
    public function login(array $credentials, $remember = false);

    /**
     * Register a new user.
     * @param  array $user
     * @return bool
     */
    public function register(array $user);

    /**
     * Activate the given used id
     * @param  int    $userId
     * @param  string $code
     * @return mixed
     */
    public function activate($userId, $code);

    /**
     * Assign a role to the given user.
     * @param  \Modules\User\Repositories\UserRepository $user
     * @param  \Modules\User\Repositories\RoleRepository $role
     * @return mixed
     */
    public function assignRole($user, $role);

    /**
     * Log the user out of the application.
     * @return mixed
     */
    public function logout();

    /**
     * Create an activation code for the given user
     * @param $user
     * @return mixed
     */
    public function createActivation($user);

    /**
     * Create a reminders code for the given user
     * @param $user
     * @return mixed
     */
    public function createReminderCode($user);

    /**
     * Completes the reset password process
     * @param $user
     * @param  string $code
     * @param  string $password
     * @return bool
     */
    public function completeResetPassword($user, $code, $password);

    /**
     * Determines if the current user has access to given permission
     * @param $permission
     * @return bool
     */
    public function hasAccess($permission);

    /**
     * Check if the user is logged in
     * @return bool
     */
    public function check();

    /**
     * Get the currently logged in user
     * @return \Modules\User\Entities\UserInterface
     */
    public function user();

    /**
     * Get the ID for the currently authenticated user
     * @return int
     */
    public function id();

    /**
     * Log a user manually in
     * @param UserInterface $user
     * @return UserInterface
     */
    public function logUserIn(UserInterface $user) : UserInterface;
}
