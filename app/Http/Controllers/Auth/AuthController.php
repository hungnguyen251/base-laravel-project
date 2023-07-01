<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
    protected $auth;

    /**
     * Controller constructor.
     *
     */
    public function __construct(AuthService $authService)
    {
        $this->middleware('auth:api')->except('login');
        $this->auth = $authService;
    }

    /**
     * Login.
     *
     */
    public function login(LoginRequest $request)
    {
        return $this->auth->login($request);
    }

    /**
     * Logout.
     *
     */
    public function logout()
    {
        return $this->auth->logout();
    }

    /**
     * Refresh Jwt Token.
     *
     */
    public function refreshToken()
    {
        return $this->auth->refresh();
    }

    /**
     * Get user info.
     *
     */
    public function me()
    {
        return $this->auth->me();
    }
}
