<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\UserPreference;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * Retrieve all articles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::paginate(10);
        return response()->json($articles);
    }

    /**
     * Display the specified article.
     *
     * @param  Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return response()->json($article);
    }

    /**
     * Handle a filtered request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filtered(Request $request)
    {
        $keyword    = $request->input('keyword');
        $source     = $request->input('source');
        $from       = $request->input('from');
        $to         = $request->input('to');


        $articles = Article::query()
            ->searchByKeyword($keyword)
            ->filterBySource($source)
            ->filterByDateRange($from, $to)
            ->paginate(10);

        return response()->json($articles);
    }



    /**
     * Retrieve personalized news for the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function personalizedNews()
    {
        $user = auth()->user();
        $preferences = UserPreference::where('user_id', $user->id)->first();

        if (!$preferences) {
            return response()->json(['message' => 'User preferences not found'], 404);
        }

        $articles = Article::query();

        // Filter by sources (using array filter)
        if (!empty($preferences->sources)) {
            $articles->whereIn('source_name', $preferences->sources);
        }

        // Filter by authors
        if (!empty($preferences->authors)) {
            $articles->whereIn('author', $preferences->authors);
        }

        return response()->json($articles->paginate(10));
    }
}
