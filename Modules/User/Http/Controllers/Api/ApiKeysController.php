<?php

namespace Modules\User\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\User\Contracts\Authentication;
use Modules\User\Entities\UserToken;
use Modules\User\Http\Requests\DeleteUserTokenRequest;
use Modules\User\Repositories\UserTokenRepository;
use Modules\User\Transformers\ApiKeysTransformer;

class ApiKeysController extends Controller
{
    /**
     * @var Authentication
     */
    private $auth;
    /**
     * @var UserTokenRepository
     */
    private $userToken;

    public function __construct(Authentication $auth, UserTokenRepository $userToken)
    {
        $this->auth = $auth;
        $this->userToken = $userToken;
    }

    public function index()
    {
        $tokens = $this->userToken->allForUser($this->auth->id());

        return ApiKeysTransformer::collection($tokens);
    }

    public function create()
    {
        $userId = $this->auth->id();
        $this->userToken->generateFor($userId);
        $tokens = $this->userToken->allForUser($userId);

        return response()->json([
            'errors' => false,
            'message' => trans('user::users.token generated'),
            'data' => ApiKeysTransformer::collection($tokens),
        ]);
    }

    public function destroy(DeleteUserTokenRequest $request, UserToken $userToken)
    {
        if ($this->userToken->allForUser($this->auth->id())->count() === 1) {
            return response()->json([
                'errors' => true,
                'message' => trans('user::users.last token can not be deleted'),
                'data' => ApiKeysTransformer::collection($this->userToken->allForUser($this->auth->id())),
            ]);
        }

        $this->userToken->destroy($userToken);
        $tokens = $this->userToken->allForUser($this->auth->id());

        return response()->json([
            'errors' => false,
            'message' => trans('user::users.token deleted'),
            'data' => ApiKeysTransformer::collection($tokens),
        ]);
    }
}
