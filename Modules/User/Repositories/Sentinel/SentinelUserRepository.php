<?php

namespace Modules\User\Repositories\Sentinel;

use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entities\Sentinel\User;
use Modules\User\Events\UserHasRegistered;
use Modules\User\Events\UserIsCreating;
use Modules\User\Events\UserIsUpdating;
use Modules\User\Events\UserWasCreated;
use Modules\User\Events\UserWasUpdated;
use Modules\User\Exceptions\UserNotFoundException;
use Modules\User\Repositories\UserRepository;

class SentinelUserRepository implements UserRepository
{
    /**
     * @var \Modules\User\Entities\Sentinel\User
     */
    protected $user;
    /**
     * @var \Cartalyst\Sentinel\Roles\EloquentRole
     */
    protected $role;

    public function __construct()
    {
        $this->user = Sentinel::getUserRepository()->createModel();
        $this->role = Sentinel::getRoleRepository()->createModel();
    }

    /**
     * Returns all the users
     * @return object
     */
    public function all()
    {
        return $this->user->all();
    }

    /**
     * Create a user resource
     * @param  array $data
     * @param  bool $activated
     * @return mixed
     */
    public function create(array $data, $activated = false)
    {
        $this->hashPassword($data);

        event($event = new UserIsCreating($data));
        $user = $this->user->create($event->getAttributes());

        if ($activated) {
            $this->activateUser($user);
            event(new UserWasCreated($user));
        } else {
            event(new UserHasRegistered($user));
        }
        app(\Modules\User\Repositories\UserTokenRepository::class)->generateFor($user->id);

        return $user;
    }

    /**
     * Create a user and assign roles to it
     * @param  array $data
     * @param  array $roles
     * @param bool $activated
     * @return User
     */
    public function createWithRoles($data, $roles, $activated = false)
    {
        $user = $this->create((array) $data, $activated);

        if (!empty($roles)) {
            $user->roles()->attach($roles);
        }

        return $user;
    }

    /**
     * Create a user and assign roles to it
     * But don't fire the user created event
     * @param array $data
     * @param array $roles
     * @param bool $activated
     * @return User
     */
    public function createWithRolesFromCli($data, $roles, $activated = false)
    {
        $this->hashPassword($data);
        $user = $this->user->create((array) $data);

        if (!empty($roles)) {
            $user->roles()->attach($roles);
        }

        if ($activated) {
            $this->activateUser($user);
        }

        return $user;
    }

    /**
     * Find a user by its ID
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->user->find($id);
    }

    /**
     * Update a user
     * @param $user
     * @param $data
     * @return mixed
     */
    public function update($user, $data)
    {
        $this->checkForNewPassword($data);

        event($event = new UserIsUpdating($user, $data));

        $user->fill($event->getAttributes());
        $user->save();

        event(new UserWasUpdated($user));

        return $user;
    }

    /**
     * @param $userId
     * @param $data
     * @param $roles
     * @internal param $user
     * @return mixed
     */
    public function updateAndSyncRoles($userId, $data, $roles)
    {
        $user = $this->user->find($userId);

        $this->checkForNewPassword($data);

        $this->checkForManualActivation($user, $data);

        event($event = new UserIsUpdating($user, $data));

        $user->fill($event->getAttributes());
        $user->save();

        event(new UserWasUpdated($user));

        if (!empty($roles)) {
            $user->roles()->sync($roles);
        }
    }

    /**
     * Deletes a user
     * @param $id
     * @throws UserNotFoundException
     * @return mixed
     */
    public function delete($id)
    {
        if ($user = $this->user->find($id)) {
            return $user->delete();
        }

        throw new UserNotFoundException();
    }

    /**
     * Find a user by its credentials
     * @param  array $credentials
     * @return mixed
     */
    public function findByCredentials(array $credentials)
    {
        return Sentinel::findByCredentials($credentials);
    }

    /**
     * Paginating, ordering and searching through pages for server side index table
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function serverPaginationFilteringFor(Request $request): LengthAwarePaginator
    {
        $roles = $this->allWithBuilder();

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $roles->whereRaw('CONCAT(first_name, " ", last_name) LIKE ? ', "%{$term}%")
                ->orWhere('email', 'LIKE', "%{$term}%")
                ->orWhere('id', $term);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $roles->orderBy($request->get('order_by'), $order);
        } else {
            $roles->orderBy('created_at', 'desc');
        }

        return $roles->paginate($request->get('per_page', 10));
    }

    public function allWithBuilder() : Builder
    {
        return $this->user->newQuery();
    }

    /**
     * Hash the password key
     * @param array $data
     */
    private function hashPassword(array &$data)
    {
        $data['password'] = Hash::make($data['password']);
    }

    /**
     * Check if there is a new password given
     * If not, unset the password field
     * @param array $data
     */
    private function checkForNewPassword(array &$data)
    {
        if (array_key_exists('password', $data) === false) {
            return;
        }

        if ($data['password'] === '' || $data['password'] === null) {
            unset($data['password']);

            return;
        }

        $data['password'] = Hash::make($data['password']);
    }

    /**
     * Check and manually activate or remove activation for the user
     * @param $user
     * @param array $data
     */
    private function checkForManualActivation($user, array &$data)
    {
        if (Activation::completed($user) && !$data['is_activated']) {
            return Activation::remove($user);
        }

        if (!Activation::completed($user) && $data['is_activated']) {
            $activation = Activation::create($user);

            return Activation::complete($user, $activation->code);
        }
    }

    /**
     * Activate a user automatically
     *
     * @param $user
     */
    private function activateUser($user)
    {
        $activation = Activation::create($user);
        Activation::complete($user, $activation->code);
    }
}
