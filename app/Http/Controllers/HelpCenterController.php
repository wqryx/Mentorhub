<?php

namespace App\Http\Controllers;

use App\Models\HelpArticle;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    /**
     * Display the help center.
     */
    public function index()
    {
        $articles = HelpArticle::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('help-center.index', compact('articles'));
    }

    /**
     * Display a specific help article.
     */
    public function show($slug)
    {
        $article = HelpArticle::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $relatedArticles = HelpArticle::where('is_published', true)
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('help-center.article', compact('article', 'relatedArticles'));
    }
}
