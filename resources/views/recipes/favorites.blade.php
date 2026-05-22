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
        container.style.opacity = '0.5';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Błąd sieciowy serwera');
            }
            return response.text();
        })
        .then(html => {
            container.innerHTML = html;
            container.style.opacity = '1';
            container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        })
        .catch(error => {
            console.error('Błąd pobierania danych AJAX:', error);
            container.style.opacity = '1';
        });
    }

    // 1. Przechwytywanie wysyłania formularza (Filtruj)
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();
            const actionUrl = form.getAttribute('action') || window.location.pathname;
            const targetUrl = `${actionUrl}?${params}`;

            window.history.pushState({}, '', targetUrl);
            fetchRecipes(targetUrl);
        });

        // Obsługa przycisku "Reset"
        form.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('button') && e.target.innerText === 'Reset') {
                e.preventDefault();
                form.reset();
                
                // Czyszczenie pól select (szablon Strongly Typed czasem je blokuje)
                const selects = form.querySelectorAll('select');
                selects.forEach(select => select.selectedIndex = 0);
                
                const inputs = form.querySelectorAll('input[type="text"]');
                inputs.forEach(input => input.value = '');

                const baseUrl = window.location.pathname;
                window.history.pushState({}, '', baseUrl);
                fetchRecipes(baseUrl);
            }
        });
    }

    // 2. Przechwytywanie kliknięć w paginację
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

    window.addEventListener('popstate', function() {
        fetchRecipes(window.location.href);
    });
});
</script>
@endsection