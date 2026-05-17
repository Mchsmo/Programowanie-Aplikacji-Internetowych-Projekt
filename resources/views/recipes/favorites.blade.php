@extends('layouts.app')

@section('title', 'Ulubione przepisy')

@section('content')
<section id="main">
    <div class="container">
        
        <header style="margin-bottom: 2em;">
            <h2>Twoje Ulubione Przepisy</h2>
            <p style="color: #777; margin-top: -0.5em;">Kolekcja dań, do których chętnie wracasz.</p>
        </header>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1em; margin-bottom: 1.5em; border-radius: 4px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        @include('recipes.partials.search-bar')

        <div class="row" style="display: flex; flex-wrap: wrap;">
            @forelse($recipes as $recipe)
                <div class="col-4 col-12-medium" style="margin-bottom: 2em; display: flex; flex-direction: column;">
                    @include('recipes.partials.recipe-card', ['recipe' => $recipe])
                </div>
            @empty
                <div class="col-12" style="text-align: center; padding: 3em 0;">
                    <p style="color: #999; font-style: italic;">Twoja lista ulubionych dań jest pusta.</p>
                </div>
            @endforelse
        </div>
        
        <div style="margin-top: 1em;">
            {{ $recipes->appends(request()->query())->links() }}
        </div>

    </div>
</section>
@endsection