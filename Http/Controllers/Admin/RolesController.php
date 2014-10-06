<?php namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Core\Permissions\PermissionManager;
use Modules\User\Http\Requests\CreateRolesRequest;
use Modules\User\Http\Requests\UpdateRoleRequest;
use Modules\User\Repositories\RoleRepository;

class RolesController extends AdminBaseController
{
    /**
     * @var PermissionManager
     */
    private $permissions;
    /**
     * @var RoleRepository
     */
    private $role;

    public function __construct(PermissionManager $permissions, RoleRepository $role)
    {
        parent::__construct();
        $this->permissions = $permissions;
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $roles = $this->role->all();

        return View::make('user::admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('user::admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRolesRequest $request
     * @return Response
     */
    public function store(CreateRolesRequest $request)
    {
        $data = array_merge($request->all(), ['permissions' => $this->permissions->clean($request->permissions)]);

        $this->role->create($data);

        Flash::success('Role created');
        return Redirect::route('dashboard.role.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        if (!$role = $this->role->find($id)) {
            return Redirect::to('user::admin.roles.index');
        }

        return View::make('user::admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param UpdateRoleRequest $request
     * @return Response
     */
    public function update($id, UpdateRoleRequest $request)
    {
        $data = array_merge($request->all(), ['permissions' => $this->permissions->clean($request->permissions)]);

        $this->role->update($id, $data);

        Flash::success('Role updated!');
        return Redirect::route('dashboard.role.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->role->delete($id);

        Flash::success('Role deleted!');
        return Redirect::route('dashboard.role.index');
    }
}