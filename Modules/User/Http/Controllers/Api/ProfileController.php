<?php

namespace Modules\User\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\User\Contracts\Authentication;
use Modules\User\Http\Requests\UpdateProfileRequest;
use Modules\User\Repositories\UserRepository;
use Modules\User\Transformers\UserProfileTransformer;

class ProfileController extends Controller
{
    /**
     * @var Authentication
     */
    private $auth;
    /**
     * @var UserRepository
     */
    private $user;

    public function __construct(Authentication $auth, UserRepository $user)
    {
        $this->auth = $auth;
        $this->user = $user;
    }

    public function findCurrentUser()
    {
        return new UserProfileTransformer($this->auth->user());
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $this->auth->user();

        $this->user->update($user, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.profile updated'),
        ]);
    }
}
