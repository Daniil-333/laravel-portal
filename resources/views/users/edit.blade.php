<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Редактирование пользователя') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Редактируем пользователя!") }}
                </div>
                <form method="POST" action="{{ route('users.save') }}" autocomplete="off" class="p-4">
                    @csrf
                    @method('put')
                    <input type="hidden" name="id" value="{{ old('id', $user->id) }}">

                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Имя')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        @php
                            $currentRole = old('role', $user->role);
                        @endphp
                        <select name="role" id="js-role-users">
                            <option value="{{ config('constants.role.GUEST') }}" @if ($currentRole == config('constants.role.EDITOR')) selected @endif>Автор</option>
                            <option value="{{ config('constants.role.EDITOR') }}" @if ($currentRole == config('constants.role.EDITOR')) selected @endif>Редактор</option>
                            <option value="{{ config('constants.role.MODERATOR') }}" @if ($currentRole == config('constants.role.MODERATOR')) selected @endif>Администратор</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-4">
                        <x-primary-button>
                            {{ __('Сохранить') }}
                        </x-primary-button>
                    </div>
                </form>
                <div class="flex justify-end p-4">
                    <form action="{{ action([App\Http\Controllers\UserController::class, 'destroy'], ['user' => $user->id]) }}" method="POST">
                        @csrf
                        <x-secondary-button type="submit">
                            {{ __('Удалить') }}
                        </x-secondary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
