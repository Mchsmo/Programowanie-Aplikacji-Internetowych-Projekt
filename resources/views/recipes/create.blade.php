@extends('layouts.app')

@section('title', 'Dodaj nowy przepis')

@push('styles')
<style>
    #content article header h2 {
        color: #111111 !important;
        font-weight: 700 !important;
        margin-bottom: 1.5em;
    }

    .recipe-form label {
        display: block;
        font-weight: 600 !important;
        color: #222222 !important;
        margin-bottom: 0.4em;
        font-size: 0.95em;
    }

    .recipe-form input[type="text"],
    .recipe-form input[type="number"],
    .recipe-form select {
        width: 100%;
        height: 3em !important;
        padding: 0 1em !important;
        background: #ffffff !important;
        border: 2px solid #666666 !important;
        border-radius: 4px !important;
        color: #111111 !important;
        font-size: 1em !important;
        margin-bottom: 1em;
    }

    .recipe-form textarea {
        width: 100%;
        padding: 0.75em 1em !important;
        background: #ffffff !important;
        border: 2px solid #666666 !important;
        border-radius: 4px !important;
        color: #111111 !important;
        font-size: 1em !important;
        font-family: inherit;
        margin-bottom: 1em;
        resize: vertical;
    }

    .recipe-form input:focus,
    .recipe-form select:focus,
    .recipe-form textarea:focus {
        border-color: #ed786a !important;
        box-shadow: 0 0 5px rgba(237, 120, 106, 0.5) !important;
    }

    .recipe-form input[type="file"].button.alt {
        display: block;
        width: 100%;
        background: #ffffff !important;
        border: 2px dashed #555555 !important;
        color: #222222 !important;
        padding: 0.75em 1em !important;
        height: auto !important;
        line-height: normal !important;
        text-align: left;
        cursor: pointer;
    }
    .recipe-form input[type="file"].button.alt:hover {
        background: #f9f9f9 !important;
        border-color: #ed786a !important;
    }

    .recipe-form small {
        color: #555555 !important;
        font-weight: 500;
        display: block;
        margin-top: 0.3em;
    }

    .recipe-form .actions {
        display: flex;
        gap: 1em;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 2em 0 0 0;
    }

    .recipe-form .actions li {
        padding: 0 !important;
        margin: 0 !important;
        flex: 1;
        max-width: 250px;
    }

    .recipe-form .actions li input[type="submit"] {
        display: block;
        width: 100%;
        height: 3em !important;
        line-height: 3em !important;
        background: #ed786a !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 4px !important;
        font-weight: bold !important;
        cursor: pointer;
        padding: 0 !important;
    }
    .recipe-form .actions li input[type="submit"]:hover {
        background: #df5d4f !important;
    }


    .recipe-form .actions li .button.alt {
        display: block;
        width: 100%;
        height: 3em !important;
        line-height: 3em !important;
        background: #f4f4f4 !important;
        color: #111111 !important;
        border: 2px solid #444444 !important;
        border-radius: 4px !important;
        font-weight: bold !important;
        text-align: center;
        text-decoration: none !important;
        padding: 0 !important;
    }
    .recipe-form .actions li .button.alt:hover {
        background: #e2e2e2 !important;
    }
</style>
@endpush

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
                                <input type="text" name="title" id="title" placeholder="np. Domowa pizza margherita" required />
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
                                <label for="prep_time">Czas przygotowania (w minutach)</label>
                                <input type="number" name="prep_time" id="prep_time" min="1" placeholder="np. 45" required />
                            </div>

                            <div class="col-12">
                                <label for="calories">Kalorie (opcjonalnie)</label>
                                <input type="number" name="calories" id="calories" min="0" placeholder="np. 650" />
                            </div>

                            <div class="col-12">
                                <label for="description">Opis przygotowania</label>
                                <textarea name="description" id="description" rows="8" placeholder="Opisz krok po kroku, jak przygotować potrawę..." required></textarea>
                            </div>

                            <div class="col-12">
                                <label for="recipe_image">Zdjęcie potrawy</label>
                                <input type="file" name="recipe_image" id="recipe_image" accept="image/*" class="button alt" />
                                <small>Dozwolone formaty: JPG, PNG. Maksymalny rozmiar: 5MB.</small>
                            </div>

                            <div class="col-12">
                                <ul class="actions">
                                    <li><input type="submit" value="Opublikuj przepis" /></li>
                                    <li><a href="{{ route('dashboard') }}" class="button alt">Anuluj</a></li>
                                </ul>
                            </div>
                        </div>
                    </form>

                    @if ($errors->any())
                        <div class="error-box">
                            <ul style="margin: 0; padding-left: 1.5em;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </article>
            </div>
        </div>
    </div>
</section>
@endsection