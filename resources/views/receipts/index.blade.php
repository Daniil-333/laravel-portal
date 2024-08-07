<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Видео') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto lg:px-8 xs:px-6 py-6">
        <div class="mb-6">
            <div class="flex justify-between items-center bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    {{ __("Здесь будет список Видео!") }}
                </div>
                @can('create', \App\Models\Receipt::class)
                <a href="{{route('receipts.create')}}" class="text-gray-100 p-4">Создать</a>
                @endcan
            </div>
        </div>
        <div class="filter mb-4">
            <div class="mb-6">
                <p class="text-gray-100">Поиск</p>
                <x-text-input class="block mt-1 w-full js-search" type="text" name="search" />
            </div>
            @if($tags->isNotEmpty())
            <div class="mb-6">
                <div class="chboxes">
                    @foreach($tags as $tag)
                        <label class="chbox">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="js-tag-filter">
                            <span>{{ $tag->title }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="filter__tags"></div>
            @if($categories->isNotEmpty())
                <select id="js-tag-category" name="category_id" class="js-tag-category">
                    <option value="0">Все</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->title }}
                        </option>
                    @endforeach
                </select>
            @endif
            <div data-sorting class="filter__sort sortFilter">
                <button type="button" class="sortFilter__btn _active" data-sort="asc" data-type="created_at">По дате</button>
                <button type="button" class="sortFilter__btn" data-sort="asc" data-type="title">По названию</button>
                <div class="sortFilter__error"></div>
            </div>
        </div>
        @if($receipts->isNotEmpty())
            <div id="receipts" class="max-w-7xl mx-auto">
                <x-receipts.items :receipts="$receipts" class="receipts max-w-7xl mx-auto lg:px-8"></x-receipts.items>
            </div>
            <p class="text-gray-100 hidden js-empty-filter">Нет рецептов</p>
        @else
            <p class="text-gray-100">Нет рецептов</p>
        @endif
    </div>
</x-app-layout>
