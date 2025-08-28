<?php
// file: app/Http/Controllers/Api/V1/WikiArticleController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WikiArticle;
use App\Models\WikiProposal;
use Illuminate\Http\Request;

class WikiArticleController extends Controller
{
    public function index()
    {
        return WikiArticle::select('id','title','slug','status','version','created_at')->orderByDesc('id')->paginate(20);
    }

    public function show(string $slug)
    {
        $a = WikiArticle::where('slug',$slug)->firstOrFail();
        return $a;
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'title'=>'required|string|max:180',
            'content_markdown'=>'required|string',
        ]);
        $a = WikiArticle::create([
            'title'=>$data['title'],
            'content_markdown'=>$data['content_markdown'],
            'status'=>'published',
            'created_by'=>$req->user()->id,
            'updated_by'=>$req->user()->id,
        ]);
        return $a;
    }

    public function proposeEdit(Request $req, string $slug)
    {
        $data = $req->validate([
            'content_markdown'=>'required|string',
            'comment'=>'nullable|string|max:300'
        ]);
        $a = WikiArticle::where('slug',$slug)->firstOrFail();
        $p = WikiProposal::create([
            'article_id'=>$a->id,
            'user_id'=>$req->user()->id,
            'content_markdown'=>$data['content_markdown'],
            'comment'=>$data['comment'] ?? null,
            'status'=>'pending'
        ]);
        return $p;
    }

    public function merge(Request $req, string $slug, int $proposalId)
    {
        // MVP: faqat article creator merge qila oladi
        $a = WikiArticle::where('slug',$slug)->firstOrFail();
        if ($a->created_by !== $req->user()->id) {
            return response()->json(['message'=>'Forbidden'], 403);
        }
        $p = WikiProposal::where('article_id',$a->id)->findOrFail($proposalId);
        $a->content_markdown = $p->content_markdown;
        $a->version = $a->version + 1;
        $a->updated_by = $req->user()->id;
        $a->save();
        $p->status = 'merged';
        $p->save();
        return $a;
    }
}

