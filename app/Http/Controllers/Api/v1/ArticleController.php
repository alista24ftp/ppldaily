<?php
namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function show(Request $request, Article $article)
    {
        if(!$article->article_enabled) return response()->json(null, 404);
        return response()->json($article);
    }

    public function trending(Request $request)
    {
        $userViews = DB::table('article_user_views')
            ->select('article_id', DB::raw('COUNT(*) as view_count'))
            ->groupBy('article_id');
        $visitorViews = DB::table('article_visitor_views')
            ->select('article_id', DB::raw('COUNT(*) as view_count'))
            ->groupBy('article_id');
        $allViews = $userViews->unionAll($visitorViews);
        $articleViews = DB::table(DB::raw("({$allViews->toSql()}) as all_views"))
            ->select('article_id', DB::raw('SUM(view_count) as total_views'))
            ->groupBy('article_id')
            ->orderByDesc('total_views')
            ->limit(10);
        $articles = DB::table('articles')
            ->joinSub($articleViews, 'article_views', function($join){
                $join->on('articles.id', '=', 'article_views.article_id');
            })
            ->get();
        return response()->json($articles->toArray());
    }
}
