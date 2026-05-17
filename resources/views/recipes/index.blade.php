@extends('layouts.app')

@section('title', 'Przeglądaj przepisy')

@section('content')
<section id="main">
    <div class="container">
        
        @include('recipes.partials.search-bar')

        <div class="row">
            @forelse($recipes as $recipe)
                <div class="col-4 col-12-medium">
                    @include('recipes.partials.recipe-card', ['recipe' => $recipe])
                </div>
            @empty
                <div class="col-12" style="text-align: center; padding: 3em 0;">
                    <p style="color: #999; font-style: italic;">Nie znaleziono przepisów spełniających kryteria.</p>
                </div>
            @endforelse
        </div>
        
        <div style="margin-top: 2em;">
            {{ $recipes->appends(request()->query())->links() }}
        </div>

    </div>
</section>
@endsection