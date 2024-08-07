<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Страница редактирования Видео') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-end mt-2 px-4">
                    <x-link :href="route('receipts.index')">
                        {{ __('Назад') }}
                    </x-link>
                </div>

                <form action="{{ action([App\Http\Controllers\ReceiptController::class, 'update'], ['receipt' => $receipt->slug]) }}" method="POST" enctype="multipart/form-data" class="p-4" name="receiptForm">
                    @csrf
                    @method('put')
                    <input type="hidden" name="id" value="<?=$receipt->id?>">
                    <div class="mb-4">
                        <p class="text-gray-100">Название</p>
                        <x-text-input class="block mt-1 w-full" type="text" name="title" :value="old('title') ?? $receipt->title" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <p class="text-gray-100">Краткое описание</p>
                        <x-textarea class="block mt-1 w-full" type="text" name="short_desc" :value="$receipt->short_desc"></x-textarea>
                        <x-input-error :messages="$errors->get('short_desc')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        @php $is_image = $receipt->file ? in_array(strtolower($receipt->file->extension), ['jpg', 'jpeg', 'png', 'bmp', 'svg']) : null @endphp
                        <img src="{{ ($is_image) ? ($receipt->file ? asset('storage/' . $receipt->file->path) : '') : '' }}"
                             alt="" class="image-preview" {{ !$is_image ? 'hidden' : '' }}>
                        <video class="video-preview mb-2" controls muted {{ $is_image ? 'hidden' : '' }}>
                            <source type="video/mp4"
                                    src="{{ $receipt->file ? (in_array(strtolower($receipt->file->extension), ['mp4', 'avi']) ? asset('storage/' . $receipt->file->path) : '') : '' }}">
                        </video>
                        <label class="upload-file">
                            <input type="file" name="file" accept="video/*, image/*">
                            <span>Выбрать файл</span>
                        </label>
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <p class="text-gray-100 mb-2">Категория</p>
                        @if($categories->isNotEmpty())
                        <select name="category_id" id="js-tag-category">
                            <option value="">Не выбрано</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    @if(old('category_id'))
                                        {{ ($category->id == old('category_id') ? 'selected' : '') }}
                                    @else
                                        {{ ($category->id == $receipt->category_id ? 'selected' : '') }}
                                    @endif
                                >
                                    {{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        @else
                            <p class="text-gray-100 ">Создайте категории</p>
                        @endif
                    </div>

                    @php
                        $ttags = [];
                        foreach ($receipt->tags as $ttag) {
                            $ttags[] = $ttag->id;
                        }
                    @endphp
                    <div class="mb-4">
                        <p class="text-gray-100 mb-2">Тэг</p>
                        @if($tags->isNotEmpty())
                            <select name="tag_id[]" multiple>
                                <option value="">Не выбрано</option>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}"
                                    @if(old('tag_id'))
                                        {{ (in_array($tag->id, old('tag_id')) ? 'selected' : '') }}
                                    @else
                                        {{ (in_array($tag->id, $ttags) ? 'selected' : '') }}
                                    @endif
                                    >
                                        {{ $tag->title }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('tag_id')" class="mt-2" />
                        @else
                            <p class="text-gray-100 ">Создайте тэги</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-gray-100 mb-2">Полное описание</p>
                        <textarea id="<?=uniqid('editor')?>" data-type="receipts" name="description" class="js-editor-classic">
                            {{ old('description') ?? $receipt->description }}
                        </textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-6 mt-2">
                        <x-primary-button class="mb-1 h-39">
                            {{ __('Сохранить') }}
                        </x-primary-button>
                    </div>
                </form>
                <div class="flex justify-end gap-6 p-4">
                    <form action="{{ action([App\Http\Controllers\ReceiptController::class, 'destroy'], ['receipt' => $receipt->slug]) }}" method="post">
                        @csrf
                        @method('delete')
                        <x-secondary-button class="mb-1 h-39">
                            {{ __('Удалить') }}
                        </x-secondary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
