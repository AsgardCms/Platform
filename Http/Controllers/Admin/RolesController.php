<?php namespace Modules\User\Http\Controllers\Admin;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Core\Permissions\PermissionManager;
use Modules\User\Http\Requests\CreateRolesRequest;
use Modules\User\Http\Requests\UpdateRoleRequest;

class RolesController extends AdminBaseController
{
    /**
     * @var \Cartalyst\Sentinel\Roles\EloquentRole
     */
    protected $roles;
    /**
     * @var PermissionManager
     */
    private $permissions;

    public function __construct(PermissionManager $permissions)
    {
        parent::__construct();
        $this->roles = Sentinel::getRoleRepository()->createModel();
        $this->permissions = $permissions;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $roles = $this->roles->all();

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
        $this->roles->create($request->all());

        Flash::success('Role created');

        return Redirect::route('dashboard.role.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        if (!$role = $this->roles->find($id)) {
            return Redirect::to('user::admin.roles.index');
        }

        // Get all permissions
        $permissions = $this->permissions->all();

        return View::make('user::admin.roles.edit', compact('role', 'permissions'));
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
        $permissions = $this->permissions->clean($request->permissions);
        $role = $this->roles->find($id);

        $role->fill($request->all());
        $role->permissions = $permissions;
        $role->save();

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
        if ($role = $this->roles->find($id))
        {
            $role->delete();

            Flash::success('Role deleted!');
            return Redirect::route('dashboard.role.index');
        }

        return Redirect::route('dashboard.role.index');
    }
}