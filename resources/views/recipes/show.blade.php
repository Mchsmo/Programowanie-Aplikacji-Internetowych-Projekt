@extends('layouts.app')

@section('title', $recipe->title)

@section('content')
<section id="main">
    <div class="container">
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
                        <h3>Komentarze i opinie</h3>
                        <p style="font-size: 0.9em; color: #bbb;"></p>
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
                            <strong class="icon solid fa-clock" style="margin-right: 0.5em;"></strong> Czas przygotowania: 
                            <span style="float: right;"><strong>{{ $recipe->prep_time }} min</strong></span>
                        </li>
                        <li>
                            <strong class="icon solid fa-fire" style="margin-right: 0.5em;"></strong> Kaloryczność: 
                            <span style="float: right;"><strong>{{ $recipe->calories ?? '?' }} kcal</strong></span>
                        </li>
                        <li>
                            <strong class="icon solid fa-star" style="margin-right: 0.5em; color: #f1c40f;"></strong> Ocena społeczności: 
                            <span style="float: right;">
                                <strong>{{ number_format($recipe->rating ?? 0.0, 1) }}/5</strong> ({{ $recipe->ratings_count ?? 0 }})
                            </span>
                        </li>
                        <li>
                            <strong class="icon solid fa-calendar-alt" style="margin-right: 0.5em;"></strong> Dodano dnia: 
                            <span style="float: right;">{{ $recipe->created_at ? $recipe->created_at->format('d.m.Y') : 'Brak danych' }}</span>
                        </li>
                    </ul>
                    <footer>
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