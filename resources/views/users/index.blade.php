<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Пользователи') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    {{ __("Зарегестрированные пользователи!") }}
                </div>
                @if(session('status'))
                    <x-status :status="session('status')" class="mb-2" />
                @endif
                <div class="p-6 pt-0">
                    <table class="text-gray-100 w-full usersTable js-users">
                        <thead>
                            <tr>
                                <th align="left">E-mail</th>
                                <th align="left">Имя</th>
                                <th align="left">Роль</th>
                                <th align="left" colspan="2">&nbsp;</th>
                            <tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td width="40%" class="pb-4">{{ $user->email }}</td>
                                <td class="pb-4">{{ $user->name }}</td>
                                <td class="pb-4">{{ $user->friendly_role }}</td>

                                @can ("manipulate", "App\User")
                                <td class="pb-4">
                                    <x-link-btn :href="action([App\Http\Controllers\UserController::class, 'edit'], ['user' => $user->id])">
                                        {{ __('Исправить') }}
                                    </x-link-btn>
                                </td>
                                <td class="pb-4">
                                    <x-secondary-button type="submit" class="h-40 bg-red-500 hover:bg-red-600 js-confirm-del-user" data-id="{{ $user->id }}">
                                        {{ __('Удалить') }}
                                    </x-secondary-button>
                                </td>
                                @endcan
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
