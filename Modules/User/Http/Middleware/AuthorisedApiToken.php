<?php

namespace Modules\User\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Repositories\UserTokenRepository;

class AuthorisedApiToken
{
    /**
     * @var UserTokenRepository
     */
    private $userToken;

    public function __construct(UserTokenRepository $userToken)
    {
        $this->userToken = $userToken;
    }

    public function handle(Request $request, \Closure $next)
    {
        if ($request->header('Authorization') === null) {
            return new Response('Forbidden', 403);
        }

        if ($this->isValidToken($request->header('Authorization')) === false) {
            return new Response('Forbidden', 403);
        }

        return $next($request);
    }

    private function isValidToken($token)
    {
        $found = $this->userToken->findByAttributes(['access_token' => $this->parseToken($token)]);

        if ($found === null) {
            return false;
        }

        return true;
    }

    private function parseToken($token)
    {
        return str_replace('Bearer ', '', $token);
    }
}
