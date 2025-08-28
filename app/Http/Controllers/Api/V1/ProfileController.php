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
            'name'=>'sometimes|string|max:100',
            'avatar_url'=>'sometimes|url',
            'bio'=>'sometimes|string|max:500'
        ]);
        $user = $req->user();
        $user->fill($data)->save();
        return $user->only(['id','name','username','avatar_url','xp','bio']);
    }
}

