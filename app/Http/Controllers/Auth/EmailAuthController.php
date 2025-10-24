<?php


// file: app/Http/Controllers/Auth/EmailAuthController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EmailAuthController extends Controller
{
    public function register(Request $req)
    {
        $data = $req->validate([
            'name'=>'required','username'=>'required|alpha_dash|unique:users,username',
            'email'=>'nullable|email|unique:users,email',
            'password'=>['required', Password::defaults()]
        ]);

        $user = User::create([
            'name'=>$data['name'],
            'username'=>$data['username'],
            'email'=>$data['email'] ?? null,
            'password'=>Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth')->plainTextToken;

        // Load relationships
        $user->load('level', 'badges');

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar_url' => $user->avatar_url,
                'bio' => $user->bio,
                'xp' => $user->xp,
                'is_admin' => $user->is_admin ?? false,
                'is_banned' => $user->is_banned ?? false,
                'level' => $user->level,
                'badges' => $user->badges,
            ]
        ]);
    }

    public function login(Request $req)
    {
        $data = $req->validate(['email'=>'required|email','password'=>'required']);
        $user = User::where('email',$data['email'])->first();
        if (!$user || !Hash::check($data['password'],$user->password)) {
            return response()->json(['message'=>'Invalid credentials'], 422);
        }

        $token = $user->createToken('auth')->plainTextToken;

        // Load relationships
        $user->load('level', 'badges');

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar_url' => $user->avatar_url,
                'bio' => $user->bio,
                'xp' => $user->xp,
                'is_admin' => $user->is_admin,
                'is_banned' => $user->is_banned,
                'level' => $user->level,
                'badges' => $user->badges,
            ]
        ]);
    }

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['ok'=>true]);
    }
}

