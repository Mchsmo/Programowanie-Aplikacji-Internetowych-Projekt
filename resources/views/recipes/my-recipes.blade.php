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

        <div id="recipes-container">
            @include('recipes.partials.recipes-list')
        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('recipes-container');
    const form = document.querySelector('.search-sort-form');

    function fetchRecipes(url) {
        // Efekt płynnego przejścia (przezroczystość podczas ładowania)
        container.style.opacity = '0.5';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Błąd połączenia z serwerem');
            }
            return response.text();
        })
        .then(html => {
            container.innerHTML = html;
            container.style.opacity = '1';
            
            // Płynne przewinięcie ekranu na górę listy po zmianie strony/filtrów
            container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        })
        .catch(error => {
            console.error('Błąd pobierania danych AJAX:', error);
            container.style.opacity = '1';
        });
    }

    // 1. Obsługa wysyłania formularza (Filtruj)
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();
            const actionUrl = form.getAttribute('action') || window.location.pathname;
            const targetUrl = `${actionUrl}?${params}`;

            // Podmieniamy adres URL w przeglądarce, by zachować filtry w historii
            window.history.pushState({}, '', targetUrl);
            fetchRecipes(targetUrl);
        });

        // Obsługa czyszczenia filtrów (Reset)
        form.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('button') && e.target.innerText === 'Reset') {
                e.preventDefault();
                form.reset();
                
                // Ręczne czyszczenie selektorów i pól tekstowych
                form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
                form.querySelectorAll('input[type="text"]').forEach(input => input.value = '');

                const baseUrl = window.location.pathname;
                window.history.pushState({}, '', baseUrl);
                fetchRecipes(baseUrl);
            }
        });
    }

    // 2. Obsługa kliknięć w przyciski stronnicowania (Paginacja)
    if (container) {
        container.addEventListener('click', function(e) {
            const anchor = e.target.closest('a');
            if (anchor && container.contains(anchor)) {
                e.preventDefault();
                const targetUrl = anchor.getAttribute('href');
                
                if (targetUrl && targetUrl !== '#') {
                    window.history.pushState({}, '', targetUrl);
                    fetchRecipes(targetUrl);
                }
            }
        });
    }

    // Obsługa strzałek "Wstecz" i "Dalej" w przeglądarce użytkownika
    window.addEventListener('popstate', function() {
        fetchRecipes(window.location.href);
    });
});
</script>
@endsection