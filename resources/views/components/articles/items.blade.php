@props(['articles'])

@if ($articles)
    <div class="articles">
        @foreach ($articles as $article)
            <div class="card">
                <div class="card__wrap">
                    <div class="card__image">
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
                    </div>
                    <div class="card__info">
                        <div>
                            <div class="text-gray-100 text-xl mb-2">{{ $article->title }}</div>
                            <div class="text-gray-100 mb-2">{{ $article->short_desc }}</div>
                        </div>
                        <div class="card__buttons articles__buttons flex
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
                                <x-link-btn :href="action([App\Http\Controllers\ArticleController::class, 'edit'], ['article' => $article->slug])" class="h-40">
                                    {{ __('Редактировать') }}
                                </x-link-btn>
                                <form action="{{ action([App\Http\Controllers\ArticleController::class, 'destroy'], ['article' => $article]) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <x-primary-button type="submit" class="h-40 bg-red-400 hover:bg-red-600 text-stone-100">
                                        {{ __('Удалить') }}
                                    </x-primary-button>
                                </form>
                            @endif
                                <x-link-btn :href="action([App\Http\Controllers\ArticleController::class, 'show'], ['article' => $article->slug])" class="h-40">
                                    {{ __('Подробнее') }}
                                </x-link-btn>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">
        {!!  $articles->links('pagination.custom') !!}
    </div>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
@endif
