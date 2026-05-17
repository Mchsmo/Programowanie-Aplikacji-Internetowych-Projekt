<div class="box" style="margin-bottom: 2em; padding: 1.5em;">
    <form action="{{ url()->current() }}" method="GET" class="search-sort-form">
        <div class="row gtr-50 aln-bottom">
            
            <div class="col-4 col-12-small">
                <label Improvement for="search" style="font-weight: bold; margin-bottom: 0.2em; display: block;">Szukaj przepisu</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="np. pizza, kurczak..." style="width: 100%; height: 2.75em;" />
            </div>

            <div class="col-3 col-12-small">
                <label for="category" style="font-weight: bold; margin-bottom: 0.2em; display: block;">Kategoria</label>
                <select name="category" id="category" style="width: 100%; height: 2.75em;">
                    <option value="">-- Wszystkie --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id_category }}" {{ request('category') == $category->id_category ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-3 col-12-small">
                <label for="sort" style="font-weight: bold; margin-bottom: 0.2em; display: block;">Sortuj według</label>
                <select name="sort" id="sort" style="width: 100%; height: 2.75em;">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Najnowsze</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Najstarsze</option>
                    <option value="prep_time_asc" {{ request('sort') == 'prep_time_asc' ? 'selected' : '' }}>Czas przygotowania: rosnąco</option>
                    <option value="prep_time_desc" {{ request('sort') == 'prep_time_desc' ? 'selected' : '' }}>Czas przygotowania: malejąco</option>
                    <option value="calories_asc" {{ request('sort') == 'calories_asc' ? 'selected' : '' }}>Kalorie: od najmniej</option>
                    <option value="calories_desc" {{ request('sort') == 'calories_desc' ? 'selected' : '' }}>Kalorie: od najwięcej</option>
                </select>
            </div>

            <div class="col-2 col-12-small" style="display: flex; gap: 0.5em;">
                <button type="submit" class="button primary solid small" style="width: 100%; height: 2.75em; padding: 0; line-height: 2.75em;">Filtruj</button>
                @if(request()->hasAny(['search', 'category', 'sort']))
                    <a href="{{ url()->current() }}" class="button alt small" style="width: 100%; height: 2.75em; padding: 0; line-height: 2.75em; text-align: center;">Reset</a>
                @endif
            </div>

        </div>
    </form>
</div>