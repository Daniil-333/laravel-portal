<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ $article->title }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">

                    <div class="flex justify-end mt-2">
                        <x-link :href="route('articles.index')">
                            {{ __('Назад') }}
                        </x-link>
                    </div>

                    <div class="sm:max-w-md mx-auto">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
                        </div>

                        <div class="mb-4 text-center">
                            <h2 class="text-gray-100 text-2xl mb-2">{{ $article->title }}</h2>
                            <p class="text-gray-100">{{ $article->short_desc }}</p>
                        </div>

                        <div class="text-gray-100 mb-2">
                            {!! $article->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
