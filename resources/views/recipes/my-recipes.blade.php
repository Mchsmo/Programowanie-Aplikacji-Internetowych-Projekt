@extends('layouts.app')

@section('title', 'Moje przepisy')

@section('content')
<section id="main">
    <div class="container">
        
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2em;">
            <h2 style="margin: 0;">Moje przepisy</h2>
            <a href="{{ route('recipes.create') }}" class="button primary icon solid fa-plus">Dodaj nowy przepis</a>
        </header>
        
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1em; margin-bottom: 1.5em; border-radius: 4px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        @include('recipes.partials.search-bar')

        <div class="row">
            @forelse($recipes as $recipe)
                <div class="col-4 col-12-medium">
                    @include('recipes.partials.recipe-card', ['recipe' => $recipe])
                    
                    <div style="display: flex; gap: 0.5em; margin-top: -1em; margin-bottom: 2em; padding: 0 1em;">
                        <a href="{{ route('recipes.edit', $recipe->id_recipe) }}" class="button alt small" style="flex: 1; text-align: center; height: 2.2em; line-height: 2.2em; padding: 0;">Edytuj</a>
                        <form action="{{ route('recipes.destroy', $recipe->id_recipe) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Czy na pewno chcesz usunąć ten przepis?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button alt small" style="width: 100%; height: 2.2em; line-height: 2.2em; padding: 0; background: #fff5f5; color: #c0392b; border-color: #f5c6cb;">Usuń</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-12" style="text-align: center; padding: 4em 0;">
                    <div class="icon solid fa-utensils" style="font-size: 3em; color: #ccc; margin-bottom: 0.5em;"></div>
                    <p style="color: #999; font-style: italic; font-size: 1.1em;">Nie stworzyłeś jeszcze żadnych przepisów lub nie pasują do filtrów.</p>
                    <a href="{{ route('recipes.create') }}" class="button alt" style="margin-top: 1em;">Dodaj swój pierwszy przepis!</a>
                </div>
            @endforelse
        </div>
        
        <div style="margin-top: 2em;">
            {{ $recipes->appends(request()->query())->links() }}
        </div>

    </div>
</section>
@endsection