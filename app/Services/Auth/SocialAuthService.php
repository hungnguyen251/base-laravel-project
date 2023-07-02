<?php

namespace App\Services\Auth;

use App\Models\User;
use Mockery\Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthService
{
    /**
     * Login Using Facebook/Google
     */
    public function loginUsingProvider($provider)
    {
        try {
            $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

            return response()->json(['url' => $url], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Facebook/Google Callback
     */
    public function callbackFromProvider($provider)
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();

            // Check User Exists
            if (!$this->checkUserExist($user->getEmail())) {

                $saveUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'password' => Hash::make($user->getName() . '@' . $user->getId()),
                    'provider' => $provider,
                    'provider_id' => $user->getId()
                ]);
            } else {
                $saveUser = User::where('email',  $user->getEmail())->update([
                    'provider' => $provider,
                    'provider_id' => $user->getId(),
                ]);
            }

            Auth::loginUsingId($saveUser->id);

            return response()->json(['message' => 'Logged in successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Check Users Email If Already There
     */
    public function checkUserExist($email)
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            return true;
        }

        return false;
    }
}
