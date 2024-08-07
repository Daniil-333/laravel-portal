<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Категории') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    {{ __("Здесь будет список категорий!") }}
                </div>
                @if(session('status'))
                    <x-status :status="session('status')" class="mb-2" />
                @endif
                @can ("manipulate", "App\User")
                    @if($categories->isNotEmpty())
                        <div class="categories">
                            @foreach ($categories as $category)
                                <div class="flex items-end gap-4 p-4">
                                    <form action="{{ action([App\Http\Controllers\CategoryController::class, 'save']) }}" method="POST" class="flex justify-between items-end gap-4 grow">
                                        @csrf
                                        @method('put')
                                        <input type="hidden" name="id" value="{{ $category->id }}">
                                        <input type="hidden" name="keyform" value="category_{{$category->id}}">
                                        @php $keyCategory = "category_{$category->id}" @endphp
                                        <div>
                                            <p class="text-gray-100">Название</p>
                                            <x-text-input class="block mt-1 w-full" type="text" name="title" :value="($errors->$keyCategory->any() ? old('title', $category->title ?? '') : $category->title)" required />
                                            @if($errors->$keyCategory)
                                                <x-input-error :messages="$errors->$keyCategory->get('title')" class="mt-2" />
                                            @endif
                                        </div>
                                        <x-primary-button class="mb-1 h-39">
                                            {{ __('Сохранить') }}
                                        </x-primary-button>
                                    </form>
                                    <form action="{{ action([App\Http\Controllers\CategoryController::class, 'destroy'], ['category' => $category->id]) }}" method="POST">
                                        @csrf
                                        <x-secondary-button type="submit" class="h-40 bg-red-500">
                                            {{ __('Удалить') }}
                                        </x-secondary-button>
                                    </form>
                                </div>
                            @endforeach
                            <x-categories.empty-form />
                        </div>
                    @else
                        <p class="p-4 text-gray-100">Нет категорий. Это будет первая:-)</p>
                        <form action="{{ action([App\Http\Controllers\CategoryController::class, 'save']) }}" method="POST" class="flex justify-between items-end gap-4 p-4">
                            @csrf
                            <input type="hidden" name="keyform" value="new_category">
                            <div>
                                <p class="text-gray-100">Название</p>
                                <x-text-input class="block mt-1 w-full" type="text" name="title" :value="old('title', $category->title ?? '')" required />
                                <x-input-error :messages="$errors->new_category->get('title')" class="mt-2" />
                            </div>
                            <div class="flex justify-end gap-4">
                                <x-primary-button class="js-create-category">
                                    {{ __('Сохранить') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
