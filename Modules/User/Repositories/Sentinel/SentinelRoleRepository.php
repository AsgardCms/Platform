<?php

namespace Modules\User\Repositories\Sentinel;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\User\Events\RoleIsCreating;
use Modules\User\Events\RoleIsUpdating;
use Modules\User\Events\RoleWasCreated;
use Modules\User\Events\RoleWasUpdated;
use Modules\User\Repositories\RoleRepository;

class SentinelRoleRepository implements RoleRepository
{
    /**
     * @var \Cartalyst\Sentinel\Roles\EloquentRole
     */
    protected $role;

    public function __construct()
    {
        $this->role = Sentinel::getRoleRepository()->createModel();
    }

    /**
     * Return all the roles
     * @return mixed
     */
    public function all()
    {
        return $this->role->all();
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
            $roles->where('name', 'LIKE', "%{$term}%")
                ->orWhere('slug', 'LIKE', "%{$term}%")
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

    /**
     * Create a role resource
     * @return mixed
     */
    public function create($data)
    {
        event($event = new RoleIsCreating($data));
        $role = $this->role->create($event->getAttributes());

        event(new RoleWasCreated($role));

        return $role;
    }

    /**
     * Find a role by its id
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->role->find($id);
    }

    /**
     * Update a role
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $role = $this->role->find($id);

        event($event = new RoleIsUpdating($role, $data));

        $role->fill($event->getAttributes());
        $role->save();

        event(new RoleWasUpdated($role));

        return $role;
    }

    /**
     * Delete a role
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $role = $this->role->find($id);

        return $role->delete();
    }

    /**
     * Find a role by its name
     * @param  string $name
     * @return mixed
     */
    public function findByName($name)
    {
        return Sentinel::findRoleByName($name);
    }

    /**
     * @inheritdoc
     */
    public function allWithBuilder() : Builder
    {
        return $this->role->newQuery();
    }
}
