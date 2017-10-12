<?php

namespace Modules\User\Http\Controllers\Api;

use Cartalyst\Sentinel\Roles\EloquentRole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\CreateRoleRequest;
use Modules\User\Http\Requests\UpdateRoleRequest;
use Modules\User\Permissions\PermissionManager;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Transformers\FullRoleTransformer;
use Modules\User\Transformers\RoleTransformer;

class RoleController extends Controller
{
    /**
     * @var RoleRepository
     */
    private $role;
    /**
     * @var PermissionManager
     */
    private $permissions;

    public function __construct(RoleRepository $role, PermissionManager $permissions)
    {
        $this->role = $role;
        $this->permissions = $permissions;
    }

    public function all()
    {
        return RoleTransformer::collection($this->role->all());
    }

    public function index(Request $request)
    {
        return RoleTransformer::collection($this->role->serverPaginationFilteringFor($request));
    }

    public function find(EloquentRole $role)
    {
        return new FullRoleTransformer($role->load('users'));
    }

    public function findNew(EloquentRole $role)
    {
        return new FullRoleTransformer($role);
    }

    public function store(CreateRoleRequest $request)
    {
        $data = $this->mergeRequestWithPermissions($request);

        $this->role->create($data);

        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.role created'),
        ]);
    }

    public function update(EloquentRole $role, UpdateRoleRequest $request)
    {
        $data = $this->mergeRequestWithPermissions($request);

        $this->role->update($role->id, $data);

        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.role updated'),
        ]);
    }

    public function destroy(EloquentRole $role)
    {
        $this->role->delete($role->id);

        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.role deleted'),
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function mergeRequestWithPermissions(Request $request)
    {
        $permissions = $this->permissions->clean($request->get('permissions'));

        return array_merge($request->all(), ['permissions' => $permissions]);
    }
}
