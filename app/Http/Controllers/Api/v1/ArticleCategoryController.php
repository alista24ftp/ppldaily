<?php
namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleCategoryController extends Controller
{
    /**
     * Get all article categories.
     */
    public function index(Request $request)
    {
        $currUser = $request->user('api');
        $excludeDisabled = !$currUser || !$currUser->can('articleCategoryAdd', ArticleCategory::class);
        $categories = ArticleCategory::excludeDisabled($excludeDisabled)->withOrder(1)->get()->toArray();
        return response()->json(ArticleCategory::buildCategoriesTree($categories));
        /*
        $categories = [];
        for($i=0; $i<200; $i++){
            array_push($categories, [
                'item' => ['id'=>$i, 'category_name'=>'Category'.$i],
                'children' => []
            ]);
        }
        return response()->json($categories);
        */
    }

    /**
     * Get article category with related articles.
     */
    public function mainArticles(Request $request, ArticleCategory $articleCategory)
    {
        if(!$articleCategory->category_enabled) return response()->json(null, 404);
        $articles = $articleCategory->getArticles()
            ->withPath("/api/v1/articlecategories/$articleCategory->id/articles");
            //->withPath("/category/$articleCategory->id");
        return response()->json($articles);
    }

    /**
     * Get article category with related categories.
     */
    public function relatedCategories(Request $request, ArticleCategory $articleCategory)
    {
        if(!$articleCategory->category_enabled) return response()->json(null, 404);
        $currUser = $request->user('api');
        $excludeDisabled = !$currUser || !$currUser->can('articleCategoryAdd', ArticleCategory::class);
        if($articleCategory->isTop()){
            $parent = $articleCategory;
            $categories = $articleCategory->getChildren()
                ->excludeDisabled($excludeDisabled)
                ->withOrder(1)
                ->get();
            //$categories->prepend($articleCategory);
        }else{
            $categories = $articleCategory->getSiblings()
                ->orWhere(function($query){
                    $query->getParent();
                })
                ->excludeDisabled($excludeDisabled)
                ->withOrder(1)
                ->get();
            $parent = null;
            foreach($categories as $i => $cat)
            {
                if($cat->id == $articleCategory->category_parent_id){
                    $parent = $categories->splice($i, 1);
                    $parent = $parent->first();
                    break;
                }
            }
            //if(!empty($parent)) $categories->prepend($parent);
        }
        // fetch articles for related categories
        /*
        $articleQueries = $categories->map(function($cat, $key){
            return DB::table('articles')
                ->select('articles.*', DB::raw('(COUNT(DISTINCT article_user_views.user_id) + COUNT(DISTINCT article_visitor_views.visitor_ip)) as views_count'))
                ->leftJoin('article_user_views', 'articles.id', '=', 'article_user_views.article_id')
                ->leftJoin('article_visitor_views', 'articles.id', '=', 'article_visitor_views.article_id')
                ->where('article_category_id', $cat->id)
                ->where(function($query){
                    $query->where('article_enabled', true)->where('deleted_at', null);
                })
                ->groupBy('articles.id')
                ->orderByDesc('views_count')
                ->limit(5);
        });
        */
        $articleQueries = $categories->map(function($cat, $key){
            return DB::table('articles')
                ->where('article_category_id', $cat->id)
                ->where(function($query){
                    $query->where('article_enabled', true)->where('deleted_at', null);
                })
                ->orderByDesc('created_at')
                ->limit(5);
        });
        if($articleQueries->count()){
            $articleList = $articleQueries->shift();
            foreach($articleQueries as $q){
                $articleList = $articleList->unionAll($q);
            }
            $articles = $articleList->get()->toArray();
        }else{
            $articles = [];
        }

        return response()->json([
            'parent' => empty($parent) ? null : $parent->toArray(),
            'categories' => $categories->toArray(),
            'articles' => $articles,
        ]);
    }
}
