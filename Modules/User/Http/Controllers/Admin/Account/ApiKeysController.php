<?php

namespace Modules\User\Http\Controllers\Admin\Account;

use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\User\Contracts\Authentication;
use Modules\User\Entities\UserToken;
use Modules\User\Repositories\UserTokenRepository;

class ApiKeysController extends AdminBaseController
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
        parent::__construct();

        $this->auth = $auth;
        $this->userToken = $userToken;
    }

    public function index()
    {
        $tokens = $this->userToken->allForUser($this->auth->id());

        $this->assetPipeline->requireJs('clipboard.js');

        return view('user::admin.account.api-keys.index', compact('tokens'));
    }

    public function create()
    {
        $this->userToken->generateFor($this->auth->id());

        return redirect()->route('admin.account.api.index')
            ->withSuccess(trans('user::users.token generated'));
    }

    public function destroy(UserToken $userToken)
    {
        $this->userToken->destroy($userToken);

        return redirect()->route('admin.account.api.index')
            ->withSuccess(trans('user::users.token deleted'));
    }
}
