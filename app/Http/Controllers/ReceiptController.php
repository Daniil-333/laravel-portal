<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceiptRequest;
use App\Models\Category;
use App\Models\File;
use App\Models\Receipt;
use App\Models\Tag;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class ReceiptController extends Controller
{
    /**
     * Показ страницы всех ресурсов.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Receipt::class);

        $receipts = $this->getAllReceipts();

        if($request->ajax()){
            $search = $request->search;
            $category = $request->category;
            $tags = $request->tags;

            $sort = $request->sort;
            $type = $request->type;

            if($category || $tags || $search) {
                $queryReceipts = Receipt::when($request->search, function($q) use($request) {
                    $q->where(fn ($q) =>
                    $q->where('title', 'like', '%'.$request->search.'%')
                        ->orWhere('short_desc', 'like', '%'.$request->search.'%')
                        ->orWhere('description', 'like', '%'.$request->search.'%')
                    );
                });

                if($request->category) {
                    $queryReceipts = $queryReceipts->where('category_id', $request->category);
                }

                if($request->tags) {
                    $queryReceipts = $queryReceipts->whereHas('tags', function ($query) use($request) {
                        $query->whereIn('tag_id', explode(',',$request->tags));
                    });
                }

                if($sort && $type) {
                    if(!in_array($sort, ['desc', 'asc'])) {
                        return [
                            'status' => 'error',
                            'msg' => 'Ошибка передачи вида сортировки'
                        ];
                    }

                    if(!in_array($type, ['created_at', 'title'])) {
                        return [
                            'status' => 'error',
                            'msg' => 'Ошибка передачи поля для сортировки'
                        ];
                    }

                    $queryReceipts = $queryReceipts->orderBy($type, $sort);
                }

                $receipts = $queryReceipts->paginate(3);
            }

            return view('components.receipts.items', compact('receipts'))
                ->render();
        }


        return view('receipts.index', [
            'receipts' => $receipts,
            'categories' => Category::all(['id', 'title']),
            'tags' => Tag::all(['id', 'title']),
        ]);
    }

    /**
     * Показ страницы создания.
     */
    public function create()
    {
        return view('receipts.create', [
            'categories' => Category::all(['id', 'title']),
            'tags' => Tag::all(['id', 'title']),
        ]);
    }

    /**
     * Добавление нового видео.
     */
    public function store(ReceiptRequest $request)
    {
        if($request->input('description')) {
            if(str_contains($request->input('description'), 'script')) {
                return redirect()->back()->withErrors([
                    'description' => ' Попытка XSS-атаки'
                ])->withInput();
            }
        }

        //проверяем slug на уникальность
        $receiptOld = $this->isUniqueSlug($request->input('title'));

        if($receiptOld) {
            return redirect()->back()->withErrors([
                'title' => 'Такое название уже есть в системе'
            ])->withInput();
        }

        $receipt = Receipt::create($request->all());

        if($request->input('tag_id')) {
            $tags = Tag::find($request->input('tag_id'));
            $receipt->tags()->attach($tags);
        }

        if($request->file()) {
            $validator = $this->validateFile($request);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            else {
                $category = Category::find($request->input('category_id'));
                $file = File::saveFile(
                    $request->file('file'),
                    $receipt->id,
                    $category->slug //имя папки для Сохранения
                );

                $receipt->file_id = $file->id;
                $receipt->save();
            }
        }

        return redirect()->action([static::class, 'index']);
    }

    /**
     * Показ страницы ресурса.
     */
    public function show(string $slug)
    {
        $receipt = Receipt::where('slug', $slug)->first();

        return view('receipts.show', [
            'receipt' => $receipt
        ]);
    }

    /**
     * Показ страницы редактирования.
     */
    public function edit(string $slug)
    {
        $this->authorize('edit', Receipt::class);

        $receipt = Receipt::where('slug', $slug)->first();

        return view('receipts.edit', [
            'receipt' => $receipt,
            'categories' => Category::all(['id', 'title']),
            'tags' => Tag::all(['id', 'title']),
        ]);
    }

    /**
     * Обновление ресурса.
     */
    public function update(ReceiptRequest $request, string $slug)
    {
        $receipt = Receipt::where('slug', $slug)->first();

        if($slug != Str::slug($request->input('title'), '-')) {
            $receiptOld = $this->isUniqueSlug($request->input('title'));
            if($receiptOld) {
                return redirect()->back()->withErrors([
                    'title' => 'Такое название уже есть в системе'
                ])->withInput();
            }
        }

        $category = Category::find($request->input('category_id'));

        $this->authorize('update', $receipt);

        if($request->input('description')) {
            if(str_contains($request->input('description'), 'script')) {
                return redirect()->back()->withErrors([
                    'description' => ' Попытка XSS-атаки'
                ])->withInput();
            }
        }

        // обновление Тэгов
        $tags = Tag::find($request->input('tag_id'));
        $receipt->tags()->sync($tags);

        // если Новая категория не совпадает со Старой
        // и нет файла в Request
        if($category->id != $receipt->category_id && !$request->hasFile('file')) {
            // перемещаем файл из Старой папки в Новую папку
            if($receipt->file_id) {
                $receipt->file->moveFile($category->slug);
            }
        }

        // обновление файла, удаление старого
        // сохранение нового в новой или существующей папке
        if($request->file()) {
            $validator = $this->validateFile($request);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            else {
                if($receipt->file) {
                    // удаляем запись из БД
                    $receipt->file->delete();
                    // удаляем файл из Старой папки
                    $receipt->file->deleteFile();
                }

                // здесь файл сохранится в Новую папку
                $file = File::saveFile(
                    $request->file('file'),
                    $receipt->id,
                    $category->slug //имя папки для Сохранения
                );

                $receipt->file_id = $file->id;
            }
        }

        $receipt->fill($request->all())->save();

        return redirect()->action([static::class, 'show'], ['receipt' => $receipt->slug]);

    }

    /**
     * Удаление ресурса.
     */
    public function destroy(string $slug)
    {
        $receipt = Receipt::where('slug', $slug)->first();
        $this->authorize('forceDelete', $receipt);

        if($receipt->file_id) {
            $receipt->file->deleteFile();
        }
        if($receipt->tags) {
            $receipt->tags()->detach();
        }
        $receipt->delete();

        $receipts = Receipt::select('*')
            ->orderBy('title')
            ->get();

        return redirect()->action([static::class, 'index'], [
            'receipts' => $receipts,
            'categories' => Category::all(['id', 'title']),
            'tags' => Tag::all(['id', 'title']),
        ]);
    }

    /**
     * Валидация файла
     * @param ReceiptRequest $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateFile(ReceiptRequest $request): \Illuminate\Validation\Validator
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
            'file' => 'file|nullable|mimetypes:video/mp4,video/avi,image/*|max:102400'
        ];
    }

    public function getMessages(): array
    {
        return [
            'file.mimetypes' => 'Недопустимое расширение файла',
            'file.max' => 'Недопустимый размер файла. Нельзя более 100Mb'
        ];
    }

    public function filter(Request $request)
    {
        $tags = $request->filter['tags'];
        $category = $request->filter['category'];
        $search = trim(strip_tags($request->filter['search']));

        if(is_array($request->filter['sort'])) {
            $sort = array_map(function ($elem) {
                return trim(strip_tags($elem));
            }, $request->filter['sort']);
        }
        $receipts = [];

        if(!$category && !$tags && !$search) {
            $receipts = $this->getAllReceipts();
        }
        else if($category || $tags || $search) {
            // необходимая группировка запроса, взято отсюда
            //https://laracasts.com/discuss/channels/laravel/laravel-when-additional-conditions-are-not-working?page=1&replyId=780588
            $queryReceipts = Receipt::when($search, function($q) use($search) {
                    $q->where(fn ($q) => $q->where('title', 'like', '%'.$search.'%')
                        ->orWhere('short_desc', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%'));
                });

            if($category) {
                $queryReceipts = $queryReceipts->where('category_id', $category);
            }

            if($tags) {
                $queryReceipts = $queryReceipts->whereHas('tags', function ($query) use($tags) {
                    $query->whereIn('tag_id', $tags);
                });
            }

            if($sort) {
                if(!array_key_exists('view', $sort) || !array_key_exists('type', $sort)) {
                    return [
                        'status' => 'error',
                        'msg' => 'Ошибка передачи данных для сортировки',
                    ];
                }

                if(!in_array($sort['view'], ['desc', 'asc'])) {
                    return [
                        'status' => 'error',
                        'msg' => 'Ошибка передачи вида сортировки'
                    ];
                }

                if(!in_array($sort['type'], ['created_at', 'title'])) {
                    return [
                        'status' => 'error',
                        'msg' => 'Ошибка передачи поля для сортировки'
                    ];
                }

                $queryReceipts = $queryReceipts->orderBy($sort['type'], strtoupper($sort['view']));
            }

            $queryReceipts = $queryReceipts->orderBy('created_at', 'DESC');
            $receipts = $queryReceipts->paginate(3);
        }

        return $receipts->items() ?
            view('components.receipts.items', compact('receipts'))
            ->render() :
            null;
    }

    public function sorting(Request $request)
    {
        $sort = $request->sort;
        $type = $request->type;

        if(!$sort && !$type) {
            return ['empty'];
        }

        if(!in_array($sort, ['desc', 'asc'])) {
            return [
                'status' => 'error',
                'msg' => 'Ошибка передачи вида сортировки'
            ];
        }

        if(!in_array($type, ['created_at', 'title'])) {
            return [
                'status' => 'error',
                'msg' => 'Ошибка передачи поля для сортировки'
            ];
        }

        $receipts = Receipt::select('*')
            ->orderBy($type, strtoupper($sort))
            ->paginate(3);

        return view('components.receipts.items', compact('receipts'))
            ->render();

    }

    public function isUniqueSlug(string $title)
    {
        return Receipt::where('slug', Str::slug($title, '-'))->first();
    }

    private function getAllReceipts()
    {
        return Receipt::select('*')
            ->orderBy('created_at', 'DESC')
            ->paginate(3);
    }
}
