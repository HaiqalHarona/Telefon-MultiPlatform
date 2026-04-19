<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class socialController extends Controller
{
    public function redirectProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callbackRequest($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('auth')->with('error', 'Login with ' . ucfirst($provider) . ' failed. Please try again later.');
        }

        $appUser = null;

        try {
            if ($provider == 'google') {
                $email = $user->getEmail();
                $providerId = $user->getId();
                
                if (empty($email)) {
                    return redirect()->route('auth')->with('error', "We could not get your email address from $provider. Please make your email public on Google or register manually.");
                }

                $appUser = User::firstOrCreate(
                    [
                        'google_id' => $providerId,
                        'email' => $email,
                    ],
                    [
                        'name' => $user->getName(),
                        'avatar' => $user->getAvatar(),
                    ]
                );
            } elseif ($provider == 'github') {
                $providerId = $user->getId();

                $appUser = User::firstOrCreate(
                    [
                        'github_id' => $providerId,
                    ],
                    [
                        'name' => $user->getName() ?? $user->getNickname(),
                        'email' => $user->getEmail(),
                        'avatar' => $user->getAvatar(),
                    ]
                );
            }

            if ($appUser) {
                Auth::login($appUser);
                return redirect()->intended('/');
            }

            return redirect()->route('auth')->with('error', 'Authentication provider not recognized.');

        } catch (\Exception $e) {
            return redirect()->route('auth')->with('error', 'An error occurred during authentication: ' . $e->getMessage());
        }
    }
}
