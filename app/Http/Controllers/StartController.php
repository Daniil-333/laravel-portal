<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class StartController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::select('*')
            ->limit(4)
            ->orderBy('id', 'DESC')
            ->paginate(3);

        if($request->ajax()) {

            return view('components.articles.items', compact('articles'))
                ->render();
        }

        return view('dashboard')->with('articles', $articles);
    }
}
