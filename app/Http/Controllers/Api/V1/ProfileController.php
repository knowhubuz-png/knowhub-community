<?php
// file: app/Http/Controllers/Api/V1/ProfileController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function me(Request $req)
    {
        $user = $req->user();
        $user->load('level', 'badges');

        return response()->json([
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
            'website_url' => $user->website_url,
            'github_url' => $user->github_url,
            'linkedin_url' => $user->linkedin_url,
        ]);
    }

    public function update(Request $req)
    {
        $data = $req->validate([
            'name' => 'sometimes|string|max:100',
            'avatar_url' => 'sometimes|url',
            'bio' => 'sometimes|string|max:500',
            'website_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'resume' => 'nullable|string|max:5000',
        ]);
        $user = $req->user();
        $user->fill($data)->save();
        return new \App\Http\Resources\UserResource($user);
    }
}

