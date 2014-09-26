<?php namespace Modules\User\Http\Controllers\Admin;

use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Core\Permissions\PermissionManager;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;

class UserController extends AdminBaseController
{
    /**
     * @var PermissionManager
     */
    private $permissions;

    /**
     * @var \Modules\Session\Entities\User
     */
    protected $users;
    /**
     * @var \Cartalyst\Sentinel\Roles\EloquentRole
     */
    protected $roles;

    public function __construct(PermissionManager $permissions)
    {
        parent::__construct();

        $this->beforeFilter('permissions');

        $this->users = Sentinel::getUserRepository();
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
        $users = $this->users->createModel()->all();

        return View::make('user::admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $roles = $this->roles->all();

        return View::make('user::admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserRequest $request
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $user = $this->users->create($request->all());
        $user->roles()->attach($request->roles);

        $code = Activation::create($user);
        Activation::complete($user, $code);

        Flash::success('User created.');
        return Redirect::route('dashboard.user.index');
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
        if (!$user = $this->users->createModel()->find($id)) {
            Flash::error('User not found');
            return Redirect::route('dashboard.user.index');
        }
        $roles = $this->roles->all();

        return View::make('user::admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param UpdateUserRequest $request
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->users->createModel()->find($id);
        $data = array_merge($request->all(), ['permissions' => $this->permissions->clean($request->permissions)]);
        $this->users->update($user, $data);

        $user->roles()->sync($request->roles);

        Flash::success('User updated.');
        return Redirect::route('dashboard.user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        if ($user = $this->users->createModel()->find($id))
        {
            $user->delete();

            Flash::success('User deleted');

            return Redirect::to('users');
        }

        Flash::error('User not found');

        return Redirect::to('users');
    }

}