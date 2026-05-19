<article class="box post" style="margin-bottom: 0; display: flex; flex-direction: column; height: 100%; padding-bottom: 1em;">
    <a href="{{ route('recipes.show', $recipe->id_recipe) }}" class="image featured" style="margin-bottom: 0.8em; overflow: hidden; height: 180px;">
        @if($recipe->image_path)
            <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->title }}" style="width: 100%; height: 100%; object-fit: cover;" />
        @else
            <img src="{{ asset('images/default-recipe.jpg') }}" alt="Brak zdjęcia" style="width: 100%; height: 100%; object-fit: cover;" />
        @endif
    </a>

    <header style="padding: 0 1em; margin-bottom: 0.8em;">
        <h3 style="margin: 0 0 0.1em 0; font-size: 1.2em; line-height: 1.2;">
            <a href="{{ route('recipes.show', $recipe->id_recipe) }}" style="color: inherit; text-decoration: none; text-transform: uppercase;">{{ $recipe->title }}</a>
        </h3>
        <p style="color: #999; font-size: 0.8em; margin: 0;">
            Kategoria: <strong>{{ $recipe->category->name ?? 'Brak' }}</strong>
        </p>
    </header>

    <div class="recipe-meta" style="padding: 0 11px; font-size: 0.85em; color: #555; margin-bottom: 1em;">
        <p style="display: flex; align-items: center; margin: 0 0 0.4em 0; padding: 0;">
            <span class="icon solid fa-clock" style="width: 1.5em; color: #777;"></span>
            <span>{{ $recipe->prep_time }} min</span>
        </p>
        
        <p style="display: flex; align-items: center; margin: 0 0 0.4em 0; padding: 0;">
            <span class="icon solid fa-fire" style="width: 1.5em; color: #777;"></span>
            <span>{{ $recipe->calories ?? '?' }} kcal</span>
        </p>

        <p style="display: flex; align-items: center; margin: 0; padding: 0;">
            <span class="icon solid fa-star" style="width: 1.5em; color: #f1c40f;"></span>
            <span style="color: #ed786a; font-weight: bold;">
                {{ number_format($recipe->averageRating(), 1) }}
            </span>
            <span style="color: #999; margin-left: 0.3em;">
                ({{ $recipe->ratings->count() }})
            </span>
        </p>
    </div>

    <div style="padding: 0 1em; margin-top: auto; margin-bottom: 0.5em;">
        <a href="{{ route('recipes.show', $recipe->id_recipe) }}" class="button alt small" style="width: 100%; text-align: center; height: 2.5em; line-height: 2.5em; padding: 0;">Zobacz przepis</a>
    </div>

    @if(request()->routeIs('recipes.favorites'))
        <div style="padding: 0 1em; border-top: 1px solid #eee; margin-top: 0.5em; padding-top: 0.8em;">
            @php
                $favRecord = $recipe->favorites->where('id_user', auth()->id())->first();
            @endphp

            <form action="{{ route('recipes.favorite.notes', $recipe->id_recipe) }}" method="POST" style="margin: 0 0 0.5em 0; padding: 0;">
                @csrf
                <label for="notes-{{ $recipe->id_recipe }}" style="font-size: 0.75em; font-weight: bold; margin-bottom: 0.3em; display: block; color: #555; text-transform: uppercase;">
                    <span class="icon solid fa-sticky-note" style="margin-right: 0.3em; color: #f39c12;"></span> Twoje uwagi:
                </label>
                
                <textarea 
                    name="notes" 
                    id="notes-{{ $recipe->id_recipe }}" 
                    rows="4" 
                    placeholder="np. Zmniejszyć ilość soli..." 
                    style="width: 100%; font-size: 0.85em; padding: 0.5em; min-height: 90px; height: 90px; resize: vertical; margin-bottom: 0.5em; line-height: 1.4;"
                >{{ $favRecord->notes ?? '' }}</textarea>
                
                <button type="submit" class="button alt small" style="width: 100%; height: 2em; line-height: 2em; padding: 0; font-size: 0.75em; text-transform: none;">
                    Zapisz notatkę
                </button>
            </form>

            <form action="{{ route('recipes.favorite.toggle', $recipe->id_recipe) }}" method="POST" style="margin: 0; padding: 0;">
                @csrf
                <button type="submit" class="button alt small icon solid fa-heart-broken" style="width: 100%; height: 2em; line-height: 2em; padding: 0; color: #bbb; border-color: #ddd; font-size: 0.75em; text-transform: none;">
                    Usuń z ulubionych
                </button>
            </form>
        </div>
    @endif
</article>