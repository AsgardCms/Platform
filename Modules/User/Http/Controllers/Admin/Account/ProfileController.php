<?php

namespace Modules\User\Http\Controllers\Admin\Account;

use Illuminate\Http\Response;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\User\Contracts\Authentication;
use Modules\User\Http\Requests\UpdateProfileRequest;
use Modules\User\Repositories\UserRepository;

class ProfileController extends AdminBaseController
{
    /**
     * @var UserRepository
     */
    private $user;

    /**
     * @var Authentication
     */
    private $auth;

    public function __construct(UserRepository $user, Authentication $auth)
    {
        parent::__construct();
        $this->user = $user;
        $this->auth = $auth;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit()
    {
        $user = $this->auth->user();

        return view('user::admin.account.profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param  UpdateProfileRequest $request
     *
     * @return Response
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = $this->auth->user();

        $this->user->update($user, $request->all());

        return redirect()->back()
            ->withSuccess(trans('user::messages.profile updated'));
    }
}
