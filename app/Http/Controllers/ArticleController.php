<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceiptRequest;
use App\Models\Article;
use App\Http\Requests\ArticleRequest;
use App\Models\File;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $articles = Article::select('*')
            ->orderBy('id', 'DESC')
            ->paginate(3);

        if($request->ajax()) {

            return view('components.articles.items', compact('articles'))
                ->render();
        }

        return view('articles.index')
            ->with('articles', $articles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        if($request->input('description')) {
            if(str_contains($request->input('description'), 'script')) {
                return redirect()->back()->withErrors([
                    'description' => ' Попытка XSS-атаки'
                ])->withInput();
            }
        }

        $article = Article::create($request->all());

        if($request->file()) {
            $validator = $this->validateFile($request);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            else {
                $filePath = File::saveImage(
                    $request->file('file'),
                );
                $article->image = $filePath;
                $article->save();
            }
        }

        return redirect()->action([static::class, 'index']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('articles.show')
            ->with('article', $article);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('articles.edit')
            ->with('article', $article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $article = Article::where('slug', $article->slug)->first();
        $this->authorize('update', $article);

        if($request->input('description')) {
            if(str_contains($request->input('description'), 'script')) {
                return redirect()->back()->withErrors([
                    'description' => ' Попытка XSS-атаки'
                ])->withInput();
            }
        }

        // обновление файла
        if($request->file()) {
            $validator = $this->validateFile($request);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            else {
                if($article->image) {
                    // удаляем старый файл
                    File::deleteImage($article->image);
                }

                // сохраняем новый файл
                $path = File::saveImage(
                    $request->file('file'),
                );

                $article->image = $path;
            }
        }

        $article->fill($request->all())->save();

        return redirect()->action([static::class, 'show'], ['article' => $article]);
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     */
    public function destroy(Article $article)
    {
        $this->authorize('forceDelete', $article);

        if($article->image) {
            Storage::delete($article->image);
        }
        $article->delete();

        return redirect()->action([static::class, 'index']);
    }

    /**
     * Валидация файла
     * @param ArticleRequest $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateFile(ArticleRequest $request): \Illuminate\Validation\Validator
    {
        return Validator::make(
            $request->all(),
            $this->getRules(),
            $this->getMessages(),
        );
    }

    public function getRules(): array
    {
        return [
            'file' => 'file|nullable|mimetypes:image/*|max:3072'
        ];
    }

    public function getMessages(): array
    {
        return [
            'file.mimetypes' => 'Недопустимое расширение файла',
            'file.max' => 'Недопустимый размер файла. Нельзя более 3Mb'
        ];
    }
}
