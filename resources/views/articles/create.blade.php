<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Добавить статью') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ action([App\Http\Controllers\ArticleController::class, 'store']) }}" method="POST" enctype="multipart/form-data" class="p-4" name="articleForm">
                    @csrf
                    <div class="mb-4">
                        <p class="text-gray-900 dark:text-gray-100">Заголовок</p>
                        <x-text-input class="block mt-1 w-full" type="text" name="title" :value="old('title')" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <p class="text-gray-900 dark:text-gray-100">Краткое описание</p>
                        <x-textarea class="block mt-1 w-full" type="text" name="short_desc" required></x-textarea>
                        <x-input-error :messages="$errors->get('short_desc')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <label class="upload-file">
                            <input type="file" name="file" accept="image/*">
                            <span>Выбрать файл</span>
                        </label>
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                    </div>

{{--                    <div class="mb-4">--}}
{{--                        <p class="text-gray-900 dark:text-gray-100 mb-2">Категория</p>--}}
{{--                        @if($categories->isNotEmpty())--}}
{{--                        <select name="category_id" >--}}
{{--                            <option value="">Не выбрано</option>--}}
{{--                            @foreach($categories as $category)--}}
{{--                                <option value="{{ $category->id }}" {{ old('category_id') ? 'selected' : '' }}>--}}
{{--                                    {{ $category->title }}--}}
{{--                                </option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />--}}
{{--                        @else--}}
{{--                            <p class="dark:text-gray-100 ">Создайте категории</p>--}}
{{--                        @endif--}}
{{--                    </div>--}}

{{--                    <div class="mb-4">--}}
{{--                        <p class="text-gray-900 dark:text-gray-100 mb-2">Тэг</p>--}}
{{--                        @if($tags->isNotEmpty())--}}
{{--                            <select name="tag_id[]" multiple>--}}
{{--                                <option value="">Не выбрано</option>--}}
{{--                                @foreach($tags as $tag)--}}
{{--                                    <option value="{{ $tag->id }}" {{ old('tag_id') ? 'selected' : '' }}>--}}
{{--                                        {{ $tag->title }}--}}
{{--                                    </option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                            <x-input-error :messages="$errors->get('tag_id')" class="mt-2" />--}}
{{--                        @else--}}
{{--                            <p class="dark:text-gray-100 ">Создайте тэги</p>--}}
{{--                        @endif--}}
{{--                    </div>--}}

                    <div>
                        <p class="text-gray-900 dark:text-gray-100 mb-2">Полное описание</p>
                        <textarea id="<?=uniqid('editor')?>" data-type="articles" name="description" class="js-editor-classic">
                            {{ old('description') }}
                        </textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="flex justify-end mt-2">
                        <x-primary-button class="mb-1 h-39">
                            {{ __('Сохранить') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
