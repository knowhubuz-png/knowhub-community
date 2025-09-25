<?php
// file: app/Http/Controllers/Api/V1/ProfileController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function me(Request $req) { return $req->user()->only(['id','name','username','avatar_url','xp','bio']); }

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

