<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware("can:manipulate,App\User"); //->except("view")
    }

    public function index()
    {
        $tags = Tag::select('*')
            ->orderBy('title')
            ->get();
        return view('tags.index', ['tags' => $tags]);
    }

    public function save(Request $request)
    {
        $validator = $this->validateForm($request);

        if ($validator->fails()) {
            return redirect()->action([static::class, 'index'], ['tags' => Tag::all()])
                ->withErrors($validator, $request->keyform)
                ->withInput();
        }

        if(!$request->has('id')) {
            Tag::create([
                'title' => $request->title,
                'slug' => Str::of($request->title)->slug('-')
            ]);
            return redirect()->action([static::class, 'index'], ['tags' => Tag::all()])->with("status", "Тэг '{$request->title}' добавлен");
        }

        $tag = Tag::findOrFail($request->id);
        $oldTitle = $tag->title;
        $tag->slug = Str::of($request->title)->slug('-');
        $tag->fill($request->all())->save();
        return redirect()->action([static::class, 'index'])->with('status', "Тэг именованный ранее {$oldTitle} переименован");
    }

    public function destroy(Tag $tag)
    {
        $name = $tag->title;
        $tag->delete();
        return redirect()->action([static::class, 'index'], ['tags' => Tag::all()])
            ->with('status', "Тэг '{$name}' удалён");
    }

    public function getRules(): array
    {
        return [
            'title' => 'required|max:30',
        ];
    }

    public function getMessages(): array
    {
        return [
            'title.required' => 'Введите название тэга',
            'title.max' => 'Имя тэга должно быть не длиннее 30 символов'
        ];
    }
}
