@props([
    'errors'
])

<form action="{{ action([App\Http\Controllers\TagController::class, 'save']) }}" method="POST" class="flex justify-between items-end gap-4 p-4">
    @csrf
    <input type="hidden" name="keyform" value="new_tag">
    <div>
        <p class="text-gray-100">Название</p>
        <x-text-input class="block mt-1 w-full" type="text" name="title" :value="($errors->new_tag->any() ? old('title') : '')" required />
        <x-input-error :messages="$errors->new_tag->get('title')" class="mt-2" />
    </div>
    <div class="flex justify-end gap-4">
        <x-primary-button>
            {{ __('Сохранить') }}
        </x-primary-button>
    </div>
</form>
