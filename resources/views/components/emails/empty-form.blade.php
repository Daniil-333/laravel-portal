@props([
    'errors'
])

<form action="{{ action([App\Http\Controllers\WlController::class, 'save']) }}" method="POST" class="flex justify-between items-end gap-4 p-4">
    @csrf
    <input type="hidden" name="keyform" value="new_email">
    <div>
        <p class="text-gray-100">Название</p>
        <x-text-input class="block mt-1 w-full" type="email" name="email" :value="($errors->new_email->any() ? old('email') : '')" required />
        <x-input-error :messages="$errors->new_email->get('email')" class="mt-2" />
    </div>
    <div class="flex justify-end gap-4">
        <x-primary-button>
            {{ __('Сохранить') }}
        </x-primary-button>
    </div>
</form>
