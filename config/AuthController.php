<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthController extends Controller
{
    // Redirection vers Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Traitement du callback Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::stateless()->driver('google')->user();
            
            $user = $this->findOrCreateUser($googleUser, 'google');
            Auth::login($user, true);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Erreur lors de l\'authentification.');
        }
    }

    // Recherche ou création de l'utilisateur
    protected function findOrCreateUser($providerUser, $provider)
    {
        $user = User::where('email', $providerUser->getEmail())->first();

        if ($user) {
            return $user;
        }

        return User::create([
            'name' => $providerUser->getName(),
            'email' => $providerUser->getEmail(),
            'provider' => $provider,
            'provider_id' => $providerUser->getId(),
            'password' => bcrypt('mot_de_passe_aleatoire'),
        ]);
    }
}
