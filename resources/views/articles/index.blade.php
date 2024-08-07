<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Статьи о разном') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto lg:px-8 xs:px-6 py-6">
        <div class="mb-6">
            <div class="flex justify-between items-center bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    {{ __("Здесь будет список статей!") }}
                </div>
                @can('create', \App\Models\Article::class)
                <a href="{{route('articles.create')}}" class="text-gray-100 p-4">Добавить</a>
                @endcan
            </div>
        </div>
        <div class="filter mb-4">
{{--            <div class="mb-6">--}}
{{--                <p class="text-gray-900 dark:text-gray-100">Поиск</p>--}}
{{--                <x-text-input class="block mt-1 w-full js-search" type="text" name="search" />--}}
{{--            </div>--}}
{{--            @if($tags->isNotEmpty())--}}
{{--            <div class="mb-6">--}}
{{--                <div class="chboxes">--}}
{{--                    @foreach($tags as $tag)--}}
{{--                        <label class="chbox">--}}
{{--                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="js-tag-filter">--}}
{{--                            <span>{{ $tag->title }}</span>--}}
{{--                        </label>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            @endif--}}
{{--            <div class="filter__tags"></div>--}}
{{--            @if($categories->isNotEmpty())--}}
{{--                <select id="js-tag-category" name="category_id" class="js-tag-category">--}}
{{--                    <option value="0">Все</option>--}}
{{--                    @foreach($categories as $category)--}}
{{--                        <option value="{{ $category->id }}">--}}
{{--                            {{ $category->title }}--}}
{{--                        </option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--            @endif--}}
        </div>
        @if($articles->isNotEmpty())
            <div id="articles" class="max-w-7xl mx-auto">
                <x-articles.items :articles="$articles" class="max-w-7xl mx-auto lg:px-8"></x-articles.items>
            </div>
            <p class="dark:text-gray-100 hidden js-empty-filter">Нет статей</p>
        @else
            <p class="dark:text-gray-100">Нет статей</p>
        @endif
    </div>
</x-app-layout>
