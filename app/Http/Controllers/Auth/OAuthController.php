<?php


// file: app/Http/Controllers/Auth/OAuthController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    public function redirectGoogle() { return Socialite::driver('google')->stateless()->redirect(); }
    public function handleGoogleCallback()
    {
        $oauth = Socialite::driver('google')->stateless()->user();
        return $this->issueToken('google', $oauth->getId(), $oauth->getName(), $oauth->getEmail(), $oauth->getAvatar());
    }

    public function redirectGithub() { return Socialite::driver('github')->stateless()->redirect(); }
    public function handleGithubCallback()
    {
        $oauth = Socialite::driver('github')->stateless()->user();
        return $this->issueToken('github', $oauth->getId(), $oauth->getNickname() ?: $oauth->getName(), $oauth->getEmail(), $oauth->getAvatar());
    }

    protected function issueToken(string $provider, string $providerId, ?string $name, ?string $email, ?string $avatar)
    {
        $user = User::firstOrCreate(
            ['provider'=>$provider,'provider_id'=>$providerId],
            [
                'name' => $name ?: 'User',
                'username' => Str::lower(Str::slug(($name ?: 'user').'-'.Str::random(5))),
                'email' => $email,
                'avatar_url' => $avatar,
                'password' => null,
            ]
        );
        $token = $user->createToken('auth')->plainTextToken;
        return response()->json(['token'=>$token,'user'=>$user]);
    }
}

