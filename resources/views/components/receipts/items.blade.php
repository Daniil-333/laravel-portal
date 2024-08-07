@props(['receipts'])

@if ($receipts)
    <div class="receipts">
        @foreach ($receipts as $receipt)
            <div class="card receipt">
                <div class="card__wrap">
                    @if($receipt->file)
                    <div class="card__video">
                        @if(in_array(strtolower($receipt->file->extension), ['jpg', 'jpeg', 'png', 'bmp', 'svg']))
                            <img src="{{ asset('storage/' . $receipt->file->path) }}"
                                 alt="{{ $receipt->title }}" class="">
                        @elseif(in_array(strtolower($receipt->file->extension), ['mp4', 'avi']))
                            <video class="video-preview" controls muted preload="metadata">
                                <source type="video/mp4" src={{ asset('storage/' . $receipt->file->path) }}>
                            </video>
                        @endif
                    </div>
                    @endif
                    <div class="card__info">
                        <div>
                            <div class="text-gray-100 text-xl mb-2">{{ $receipt->title }}</div>
                            <div class="text-gray-100 mb-2">{{ $receipt->short_desc }}</div>
                        </div>
                        <div class="card__buttons receipt__buttons flex
                            @if(
                                Auth::user()->role == Config::get('constants.role.MODERATOR') ||
                                (Auth::user()->role == Config::get('constants.role.EDITOR'))
                            )
                            justify-between
                            @else
                            justify-end
                            @endif
                            items-end gap-4">
                            @if(
                                Auth::user()->role == Config::get('constants.role.MODERATOR') ||
                                (Auth::user()->role == Config::get('constants.role.EDITOR'))
                            )
                                <x-link-btn :href="action([App\Http\Controllers\ReceiptController::class, 'edit'], ['receipt' => $receipt->slug])" class="h-40">
                                    {{ __('Редактировать') }}
                                </x-link-btn>
                                <form action="{{ action([App\Http\Controllers\ReceiptController::class, 'destroy'], ['receipt' => $receipt->slug]) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <x-primary-button type="submit" class="h-40 bg-red-400 hover:bg-red-600 text-stone-100">
                                        {{ __('Удалить') }}
                                    </x-primary-button>
                                </form>
                            @endif
                                <x-link-btn :href="action([App\Http\Controllers\ReceiptController::class, 'show'], ['receipt' => $receipt->slug])" class="h-40">
                                    {{ __('Подробнее') }}
                                </x-link-btn>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">
        {!!  $receipts->links('pagination.custom') !!}
    </div>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
@endif
