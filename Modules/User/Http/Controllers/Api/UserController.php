<?php

namespace Modules\User\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Contracts\Authentication;
use Modules\User\Entities\Sentinel\User;
use Modules\User\Events\UserHasBegunResetProcess;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Permissions\PermissionManager;
use Modules\User\Repositories\UserRepository;
use Modules\User\Transformers\FullUserTransformer;
use Modules\User\Transformers\UserTransformer;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $user;
    /**
     * @var PermissionManager
     */
    private $permissions;

    public function __construct(UserRepository $user, PermissionManager $permissions)
    {
        $this->user = $user;
        $this->permissions = $permissions;
    }

    public function index(Request $request)
    {
        return UserTransformer::collection($this->user->serverPaginationFilteringFor($request));
    }

    public function find(User $user)
    {
        return new FullUserTransformer($user->load('roles'));
    }

    public function findNew(User $user)
    {
        return (new FullUserTransformer($user))->additional(['data' => ['is_new' => true]]);
    }

    public function store(CreateUserRequest $request)
    {
        $data = $this->mergeRequestWithPermissions($request);

        $this->user->createWithRoles($data, $request->get('roles'), $request->get('is_activated'));

        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.user created'),
        ]);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $data = $this->mergeRequestWithPermissions($request);

        $this->user->updateAndSyncRoles($user->id, $data, $request->get('roles'));

        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.user updated'),
        ]);
    }

    public function destroy(User $user)
    {
        $this->user->delete($user->id);

        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.user deleted'),
        ]);
    }

    public function sendResetPassword(User $user, Authentication $auth)
    {
        $code = $auth->createReminderCode($user);

        event(new UserHasBegunResetProcess($user, $code));

        return response()->json([
            'errors' => false,
            'message' => trans('user::auth.reset password email was sent'),
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function mergeRequestWithPermissions(Request $request) : array
    {
        $permissions = $this->permissions->clean($request->get('permissions'));

        return array_merge($request->all(), ['permissions' => $permissions]);
    }
}
