<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Избранные люди :-)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    {{ __("Белый список") }}
                </div>
                @if(session('status'))
                    <x-status :status="session('status')" class="mb-2" />
                @endif
                @can ("manipulate", "App\User")
                    @if($emails->isNotEmpty())
                        <div>
                            @foreach ($emails as $email)
                                <div class="flex items-end gap-4 p-4">
                                    <form action="{{ action([App\Http\Controllers\WlController::class, 'save']) }}" method="POST" class="flex justify-between items-end gap-4 grow">
                                        @csrf
                                        @method('put')
                                        <input type="hidden" name="id" value="{{ $email->id }}">
                                        <input type="hidden" name="keyform" value="email_{{$email->id}}">
                                        @php $keyTag = "email_{$email->id}" @endphp
                                        <div>
                                            <p class="text-gray-100">E-mail</p>
                                            <x-text-input class="block mt-1 w-full" type="email" name="email" :value="($errors->$keyTag->any() ? old('email', $email->email ?? '') : $email->email)" required />
                                            @if($errors->$keyTag)
                                                <x-input-error :messages="$errors->$keyTag->get('email')" class="mt-2" />
                                            @endif
                                        </div>
                                        <x-primary-button class="mb-1 h-39">
                                            {{ __('Сохранить') }}
                                        </x-primary-button>
                                    </form>
                                    <form action="{{ action([App\Http\Controllers\WlController::class, 'destroy'], ['wl' => $email->id]) }}" method="POST">
                                        @csrf
                                        <x-secondary-button type="submit" class="h-40">
                                            {{ __('Удалить') }}
                                        </x-secondary-button>
                                    </form>
                                </div>
                            @endforeach
                            <x-emails.empty-form />
                        </div>
                    @else
                        <p class="p-4 text-gray-100">Нет email'ов. Это будет первый :-)</p>
                        <form action="{{ action([App\Http\Controllers\WlController::class, 'save']) }}" method="POST" class="flex justify-between items-end gap-4 p-4">
                            @csrf
                            <input type="hidden" name="keyform" value="new_email">
                            <div>
                                <p class="text-gray-100">E-mail</p>
                                <x-text-input class="block mt-1 w-full" type="email" name="email" :value="($errors->new_email->any() ? old('email') : '')" required />
                                <x-input-error :messages="$errors->new_email->get('email')" class="mt-2" />
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
