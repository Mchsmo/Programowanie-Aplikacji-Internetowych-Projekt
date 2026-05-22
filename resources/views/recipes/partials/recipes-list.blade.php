<div class="row">
    @forelse($recipes as $recipe)
        <div class="col-4 col-12-medium" style="margin-bottom: 3em;">
            @include('recipes.partials.recipe-card', ['recipe' => $recipe])
            
            @if(Route::is('recipes.my-recipes'))
                <div style="display: flex; gap: 0.5em; margin-top: -1em; padding: 0 1em;">
                    <form action="{{ route('recipes.destroy', $recipe->id_recipe) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Czy na pewno chcesz usunąć ten przepis?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button alt small" style="width: 100%; height: 2.2em; line-height: 2.2em; padding: 0; background: #862222; color: #fff; border-color: #55161c;">
                            Usuń przepis
                        </button>
                    </form>
                </div>
            @endif
        </div>
    @empty
        <div class="col-12" style="text-align: center; color: #999; padding: 4em 0;">
            <p>Brak przepisów spełniających kryteria wyszukiwania.</p>
        </div>
    @endforelse
</div>

@include('recipes.partials.pagination', ['paginator' => $recipes])