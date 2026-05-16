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

        <div class="box" style="margin-bottom: 2em; padding: 1.5em;">
            <form method="GET" action="{{ route('recipes.index') }}" class="row g-3">
                <div class="col-6 col-12-small">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Wpisz nazwę przepisu..." style="width: 100%;" />
                </div>
                <div class="col-4 col-12-small">
                    <select name="category" style="width: 100%;">
                        <option value="">-- Wszystkie kategorie --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id_category }}" {{ request('category') == $category->id_category ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2 col-12-small">
                    <button type="submit" class="button icon solid fa-search" style="width: 100%; height: 3em; line-height: 3em; padding: 0; text-align: center;    ">Szukaj</button>
                </div>
            </form>
        </div>

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
                            <p style="font-size: 0.85em; color: #777; margin-top: -0.5em; margin-bottom: 1em;">
                                Autor przepisu: <strong style="color: #ed786a;">{{ $recipe->user->name ?? 'Anonim' }}</strong>
                                @if($recipe->category)
                                    | Kategoria: <em>{{ $recipe->category->name }}</em>
                                @endif
                            </p>
                        </header>
                        <p>{{ Str::limit($recipe->description, 100) }}</p>
                        
                        <ul class="icons">
                            <li class="icon solid fa-clock"> {{ $recipe->prep_time }} min</li>
                            <li class="icon solid fa-fire"> {{ $recipe->calories ?? '?' }} kcal</li>
                            <li class="icon solid fa-star" style="color: #f1c40f;"> 
                                {{ number_format($recipe->rating ?? 0.0, 1) }}/5 ({{ $recipe->ratings_count ?? 0 }})
                            </li>
                        </ul>
                        
                        <footer>
                            <ul class="actions">
                                <li><a href="{{ route('recipes.show', $recipe->id_recipe) }}" class="button alt">Zobacz przepis</a></li>
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