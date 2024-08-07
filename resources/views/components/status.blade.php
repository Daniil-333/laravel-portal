@props(['status'])

@if ($status)
    <ul {{ $attributes->merge(['class' => 'text-sm text-green-600 dark:text-green-400 space-y-1']) }}>
        <li>{{ $status }}</li>
    </ul>
@endif
