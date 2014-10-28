<?php namespace Modules\User\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Commander\CommanderTrait;
use Laracasts\Flash\Flash;
use Modules\Core\Contracts\Authentication;
use Modules\User\Exceptions\InvalidOrExpiredResetCode;
use Modules\User\Exceptions\UserNotFoundException;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Http\Requests\RegisterRequest;
use Modules\User\Http\Requests\ResetCompleteRequest;
use Modules\User\Http\Requests\ResetRequest;

class AuthController
{
    use CommanderTrait;

    /**
     * @var AuthenticationRepository
     */
    private $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function getLogin()
    {
        return View::make('user::public.login');
    }

    public function postLogin(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        $remember = (bool)$request->get('remember_me', false);

        $error = $this->auth->login($credentials, $remember);
        if (!$error) {
            Flash::success('Successfully logged in.');
            return Redirect::intended('/');
        }

        Flash::error($error);
        return Redirect::back()->withInput();
    }

    public function getRegister()
    {
        return View::make('user::public.register');
    }

    public function postRegister(RegisterRequest $request)
    {
        $this->execute('Modules\User\Commands\RegisterNewUserCommand', $request->all());

        Flash::success('Account created. Please check your email to activate your account.');

        return Redirect::route('register');
    }

    public function getLogout()
    {
        $this->auth->logout();

        return Redirect::route('login');
    }

    public function getActivate($userId, $code)
    {
        if ($this->auth->activate($userId, $code)) {
            Flash::success('Account activated. You can now login.');
            return Redirect::route('login');
        }
        Flash::error('There was an error with the activation.');
        return Redirect::route('register');
    }

    public function getReset()
    {
        return View::make('user::public.reset.begin');
    }

    public function postReset(ResetRequest $request)
    {
        try {
            $this->execute('Modules\User\Commands\BeginResetProcessCommand', $request->all());
        } catch (UserNotFoundException $e) {
            Flash::error('No user with that email address belongs in our system.');

            return Redirect::back()->withInput();
        }

        Flash::success('Check your email to reset your password.');

        return Redirect::route('reset');
    }

    public function getResetComplete()
    {
        return View::make('user::public.reset.complete');
    }

    public function postResetComplete($userId, $code, ResetCompleteRequest $request)
    {
        try {
            $this->execute(
                'Modules\User\Commands\CompleteResetProcessCommand',
                array_merge($request->all(), ['userId' => $userId, 'code' => $code])
            );
        } catch (UserNotFoundException $e) {
            Flash::error('The user no longer exists.');
            return Redirect::back()->withInput();
        } catch (InvalidOrExpiredResetCode $e) {
            Flash::error('Invalid or expired reset code.');
            return Redirect::back()->withInput();
        }

        Flash::success('Password has been reset. You can now login with your new password.');
        return Redirect::route('login');
    }
}
