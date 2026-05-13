@extends('layouts.app')

@section('title', 'Przeglądaj przepisy')

@section('content')
<section id="main">
    <div class="container">
        <header>
            <h2>Wszystkie przepisy</h2>
        </header>

        @if(session('success'))
            <div style="background: #aed6a0; color: #333; padding: 1em; margin-bottom: 2em; border-radius: 4px; text-align: center;">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">
            @forelse($recipes as $recipe)
                <div class="col-4 col-12-medium">
                    <section class="box">
                        <a href="#" class="image featured">
                            @if($recipe->image_path)
                                <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->title }}" style="height: 200px; object-fit: cover;" />
                            @else
                                <img src="{{ asset('images/default-recipe.jpg') }}" alt="Brak zdjęcia" style="height: 200px; object-fit: cover;" />
                            @endif
                        </a>
                        <header>
                            <h3>{{ $recipe->title }}</h3>
                        </header>
                        <p>{{ Str::limit($recipe->description, 100) }}</p>
                        
                        <ul class="icons">
                            <li class="icon solid fa-clock"> {{ $recipe->prep_time }} min</li>
                            <li class="icon solid fa-fire"> {{ $recipe->calories ?? '?' }} kcal</li>
                        </ul>
                        
                        <footer>
                            <ul class="actions">
                                <li><a href="#" class="button alt">Zobacz przepis</a></li>
                            </ul>
                        </footer>
                    </section>
                </div>
            @empty
                <div class="col-12">
                    <p style="text-align: center;">Nie znaleziono jeszcze żadnych przepisów.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection