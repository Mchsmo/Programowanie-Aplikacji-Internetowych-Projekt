@extends('layouts.app')

@section('title', $recipe->title)

@push('styles')
<style>
    /* Poprawa widoczności i układu dla pól komentarzy i ocen */
    textarea, select {
        width: 100% !important;
        background: #ffffff !important;
        border: 2px solid #666666 !important;
        border-radius: 4px !important;
        color: #111111 !important;
        padding: 0.5em 1em !important;
        font-family: inherit;
    }
    
    select {
        height: 3em !important;
    }

    textarea:focus, select:focus {
        border-color: #ed786a !important;
    }

    /* Poprawa czytelności opisu */
    .recipe-description {
        line-height: 1.7;
        color: #222222;
        font-size: 1.05em;
    }
</style>
@endpush

@section('content')
<section id="main">
    <div class="container">
        
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1em; margin-bottom: 1.5em; border-radius: 4px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            
            <div class="col-8 col-12-medium">
                <article class="box post">
                    <header>
                        <h2>{{ $recipe->title }}</h2>
                        <p style="color: #777; margin-top: -0.5em;">
                            Opublikowane przez: <strong style="color: #ed786a;">{{ $recipe->user->name ?? 'Anonim' }}</strong> 
                            @if($recipe->category)
                                w kategorii <em>{{ $recipe->category->name }}</em>
                            @endif
                        </p>
                    </header>
                    
                    <span class="image featured">
                        @if($recipe->image_path)
                            <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->title }}" style="max-height: 450px; object-fit: cover;" />
                        @else
                            <img src="{{ asset('images/default-recipe.jpg') }}" alt="Brak zdjęcia" style="max-height: 450px; object-fit: cover;" />
                        @endif
                    </span>

                    <h3>Opis przygotowania</h3>
                    <p>{!! nl2br(e($recipe->description)) !!}</p>
                    
                    <hr style="border: 0; border-top: 1px solid #ddd; margin: 2em 0;" />
                    
                    <section>
                        <h3>Komentarze ({{ $recipe->commentsCount() }})</h3>
                        
                        @auth
                            <form action="{{ route('comments.store', $recipe->id_recipe) }}" method="POST" style="margin-bottom: 2em;">
                                @csrf
                                <div style="margin-bottom: 1em;">
                                    <label for="content" style="font-weight: bold; margin-bottom: 0.5em; display: block;">Dodaj swoją opinię:</label>
                                    <textarea name="content" id="content" rows="4" placeholder="Jak wyszło danie? Podziel się wrażeniami..." required></textarea>
                                </div>
                                <button type="submit" class="button">Wyślij komentarz</button>
                            </form>
                        @else
                            <p style="background: #f9f9f9; padding: 1em; text-align: center; border-radius: 4px; border: 1px solid #eee;">
                                <a href="{{ route('login') }}" style="color: #ed786a; font-weight: bold;">Zaloguj się</a>, aby dodać komentarz.
                            </p>
                        @endauth

                        <div class="comments-list" style="margin-top: 2em;">
                            @forelse($recipe->comments as $comment)
                                <div class="comment-item" style="border-bottom: 1px solid #eee; padding: 1.2em 0;">
                                    <p style="margin-bottom: 0.4em;">
                                        <strong style="color: #ed786a;">{{ $comment->user->name ?? 'Anonim' }}</strong> 
                                        <span style="font-size: 0.8em; color: #999; float: right;">
                                            {{ $comment->date_added ? $comment->date_added->format('d.m.Y H:i') : '' }}
                                        </span>
                                    </p>
                                    <p style="color: #444; margin: 0; line-height: 1.5;">{{ $comment->content }}</p>
                                </div>
                            @empty
                                <p style="color: #999; font-style: italic;">Ten przepis nie został jeszcze skomentowany. Dodaj pierwszą opinię!</p>
                            @endforelse
                        </div>
                    </section>
                </article>
            </div>

            <div class="col-4 col-12-medium">
                <section class="box">
                    <header>
                        <h3>Metryka dania</h3>
                    </header>
                    <ul class="divided">
                        <li>
                            <strong class="icon solid fa-clock" style="margin-right: 0.5em;"></strong> Czas: 
                            <span style="float: right;"><strong>{{ $recipe->prep_time }} min</strong></span>
                        </li>
                        <li>
                            <strong class="icon solid fa-fire" style="margin-right: 0.5em;"></strong> Kalorie: 
                            <span style="float: right;"><strong>{{ $recipe->calories ?? '?' }} kcal</strong></span>
                        </li>
                        <li>
                            <strong class="icon solid fa-star" style="margin-right: 0.5em; color: #f1c40f;"></strong> Średnia ocena: 
                            <span style="float: right;">
                                <strong>{{ number_format($recipe->averageRating(), 1) }}/5</strong> ({{ $recipe->ratings->count() }})
                            </span>
                        </li>
                    </ul>

                    <div style="margin-top: 1.5em; padding-bottom: 1.5em; border-bottom: 1px solid #eee;">
                        @auth
                            @php
                                $isFavorite = $recipe->favorites()->where('id_user', auth()->id())->exists();
                            @endphp

                            <form action="{{ route('recipes.favorite.toggle', $recipe->id_recipe) }}" method="POST">
                                @csrf
                                @if($isFavorite)
                                    <button type="submit" class="button primary solid small" style="width: 100%; background: #e74c3c; box-shadow: none;">
                                        <span class="icon solid fa-heart" style="margin-right: 0.5em;"></span> Usuń z ulubionych
                                    </button>
                                @else
                                    <button type="submit" class="button alt small" style="width: 100%;">
                                        <span class="icon regular fa-heart" style="margin-right: 0.5em; color: #e74c3c;"></span> Dodaj do ulubionych
                                    </button>
                                @endif
                            </form>
                        @else
                            <p style="font-size: 0.85em; color: #999; text-align: center; margin: 0;">
                                <a href="{{ route('login') }}">Zaloguj się</a>, aby dodać przepis do ulubionych.
                            </p>
                        @endauth
                    </div>

                    <div style="margin-top: 1.5em; padding-top: 1.5em; border-top: 1px solid #eee;">
                        <h4>Wystaw swoją ocenę:</h4>
                        @auth
                            <form action="{{ route('ratings.store', $recipe->id_recipe) }}" method="POST">
                                @csrf
                                <div style="margin-bottom: 1em;">
                                    <select name="rating" style="width: 100%; height: 2.8em;">
                                        <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                        <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                        <option value="3">⭐⭐⭐ (3/5)</option>
                                        <option value="2">⭐⭐ (2/5)</option>
                                        <option value="1">⭐ (1/5)</option>
                                    </select>
                                </div>
                                <button type="submit" class="button alt small" style="width: 100%;">Zapisz ocenę</button>
                            </form>
                        @else
                            <p style="font-size: 0.85em; color: #999; text-align: center;">
                                <a href="{{ route('login') }}">Zaloguj się</a>, aby ocenić przepis.
                            </p>
                        @endauth
                    </div>

                    <footer style="margin-top: 2.5em;">
                        <ul class="actions">
                            <li><a href="{{ route('recipes.index') }}" class="button alt icon solid fa-arrow-left">Powrót do listy</a></li>
                        </ul>
                    </footer>
                </section>
            </div>

        </div>
    </div>
</section>
@endsection