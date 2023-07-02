<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\SocialAuthService;

class SocialAuthController extends Controller
{
    protected $socialAuth;

    /**
     * Controller constructor.
     *
     */
    public function __construct(SocialAuthService $socialAuthService)
    {
        $this->socialAuth = $socialAuthService;
    }

    /**
     * Login Using Facebook/Google
     */
    public function socialLogin($provider)
    {
        return $this->socialAuth->loginUsingProvider($provider);
    }

    /**
     * Facebook/Google Callback
     */
    public function socialCallback($provider)
    {
        return $this->socialAuth->callbackFromProvider($provider);
    }
}
