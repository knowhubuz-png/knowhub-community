<?php

// file: app/Http/Controllers/Api/V1/VoteController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoteRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function vote(VoteRequest $req)
    {
        $data = $req->validated();
        $model = $data['votable_type'] === 'post' ? Post::class : Comment::class;

        return DB::transaction(function () use ($model, $data, $req) {
            $votable = $model::findOrFail($data['votable_id']);

            $vote = Vote::updateOrCreate(
                ['user_id'=>$req->user()->id, 'votable_id'=>$votable->id, 'votable_type'=>$model],
                ['value'=>$data['value']]
            );

            // Recalculate score
            $sum = Vote::where('votable_id',$votable->id)->where('votable_type',$model)->sum('value');
            $votable->score = $sum;
            $votable->save();

            return response()->json(['ok'=>true, 'score'=>$sum, 'vote'=>$vote->value]);
        });
    }
}

