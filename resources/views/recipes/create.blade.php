@extends('layouts.app')

@section('title', 'Dodaj nowy przepis')

@section('content')
<section id="main">
    <div class="container">
        <div class="row gtr-150">

            <div class="col-8 col-12-medium" id="content">
                <article>
                    <header>
                        <h2>Dodaj nowy przepis</h2>
                    </header>

                    <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data" class="recipe-form">
                        @csrf

                        <div class="row gtr-50">
                            <div class="col-12">
                                <label for="title">Nazwa przepisu</label>
                                <input type="text" name="title" id="title" required />
                            </div>

                            <div class="col-6 col-12-small">
                                <label for="id_category">Kategoria</label>
                                <select name="id_category" id="id_category" required>
                                    <option value="">-- Wybierz kategorię --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-12-small">
                                <label for="prep_time">Czas przygotowania (minuty)</label>
                                <input type="number" name="prep_time" id="prep_time" placeholder="np. 45" required />
                            </div>

                            <div class="col-12">
                                <label for="calories">Kalorie (opcjonalnie)</label>
                                <input type="number" name="calories" id="calories" placeholder="np. 500" />
                            </div>

                            <div class="col-12">
                                <label for="description">Opis i przygotowanie</label>
                                <textarea name="description" id="description" placeholder="Opisz jak przygotować danie..." rows="6" required></textarea>
                            </div>

                            <div class="col-12">
                                <label for="recipe_image">Zdjęcie potrawy</label>
                                <input type="file" name="recipe_image" id="recipe_image" accept="image/*" class="button alt" style="width: 100%;" />
                                <small>Dozwolone formaty: JPG, PNG. Maksymalny rozmiar: 5MB.</small>
                            </div>

                            <div class="col-12" style="margin-top: 2em; text-align: center;">
                                <ul class="actions">
                                    <li><input type="submit" value="Opublikuj przepis" /></li>
                                    <li><a href="{{ route('dashboard') }}" class="button alt">Anuluj</a></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection