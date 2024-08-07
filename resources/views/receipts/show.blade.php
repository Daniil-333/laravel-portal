<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ $receipt->title }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">

                    <div class="flex justify-end mt-2">
                        <x-link :href="route('receipts.index')">
                            {{ __('Назад') }}
                        </x-link>
                    </div>

                    <div class="sm:max-w-md mx-auto">
                        @if($receipt->file)
                        <div class="flex justify-center mb-4">
                            @if(in_array(strtolower($receipt->file->extension), ['jpg', 'jpeg', 'png', 'bmp', 'svg']))
                                <img src="{{ asset('storage/' . $receipt->file->path) }}"
                                     alt="{{ $receipt->title }}" class="">
                            @elseif(in_array(strtolower($receipt->file->extension), ['mp4', 'avi']))
                            <video class="video-preview" controls muted>
                                <source type="video/mp4" src="{{ asset('storage/' . $receipt->file->path) }}">
                            </video>
                            @endif
                        </div>
                        @endif

                        <div class="mb-4 text-center">
                            <h2 class="text-gray-100 text-2xl mb-2">{{ $receipt->title }}</h2>
                            <p class="text-gray-100">{{ $receipt->short_desc }}</p>
                        </div>

                        <div class="text-gray-100 mb-2">
                            {!! $receipt->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
