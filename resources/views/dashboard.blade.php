<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Портал статей и видео') }}
        </h2>

        <x-nav-link href="/">
            {{ __('На главную') }}
        </x-nav-link>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-100 mb-6">
                    {{ __("Вы в личном кабинете!") }}
                </div>

                @if($articles->isNotEmpty())
                    <div id="articles" class="max-w-7xl mx-auto">
                        <x-articles.items :articles="$articles" class="max-w-7xl mx-auto lg:px-8"></x-articles.items>
                    </div>
                    <p class="text-gray-100 hidden js-empty-filter">Нет статей</p>
                @else
                    <p class="text-gray-100">Нет статей</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
