<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware("can:manipulate,App\User"); //->except("view")
    }

    public function index()
    {
        $categories = Category::select('*')
            ->orderBy('title')
            ->get();
        return view('categories.index', ['categories' => $categories]);
    }

    public function save(Request $request)
    {
        $validator = $this->validateForm($request);

        // $request->keyform - ключ, идентифицирующий пакет ошибок формы
        if ($validator->fails()) {
            return redirect()->action([static::class, 'index'], ['categories' => Category::all()])
                ->withErrors($validator, $request->keyform)
                ->withInput();
        }

        if(!$request->has('id')) {
            Category::create([
                'title' => $request->title,
                'slug' => Str::of($request->title)->slug('-')
            ]);
            return redirect()->action([static::class, 'index'], ['categories' => Category::all()])->with("status", "Категория '{$request->title}' добавлена");
        }

        $category = Category::findOrFail($request->id);
        $category->slug = Str::of($request->title)->slug('-');
        $category->fill($request->all())->save();
        return redirect()->action([static::class, 'index'])->with('status', "Категория под номером {$category->id} исправлена");
    }

    public function destroy(Category $category)
    {
        $name = $category->title;
        $category->delete();
        return redirect()->action([static::class, 'index'], ['categories' => Category::all()])
            ->with('status', "Категория '{$name}' удалена");
    }

    public function getRules(): array
    {
        return [
            'title' => 'required|max:100',
        ];
    }

    public function getMessages(): array
    {
        return [
            'title.required' => 'Введите название категории',
            'title.max' => 'Имя категории должно быть не длиннее 100 символов'
        ];
    }
}
