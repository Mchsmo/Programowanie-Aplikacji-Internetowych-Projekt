@extends('layouts.app')

@section('title', 'Przeglądaj przepisy')

@section('content')
<section id="main">
    <div class="container">
        
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
        // Efekt ładowania (półprzezroczystość listy)
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
            
            // Płynne przewinięcie ekranu na górę wyników wyszukiwania
            container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        })
        .catch(error => {
            console.error('Błąd pobierania danych AJAX:', error);
            container.style.opacity = '1';
        });
    }

    // 1. Obsługa wysyłania formularza wyszukiwania (przycisk Filtruj)
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();
            const actionUrl = form.getAttribute('action') || window.location.pathname;
            const targetUrl = `${actionUrl}?${params}`;

            // Zmiana adresu URL w przeglądarce bez przeładowania
            window.history.pushState({}, '', targetUrl);
            fetchRecipes(targetUrl);
        });

        // Obsługa resetowania filtrów (przycisk Reset)
        form.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('button') && e.target.innerText === 'Reset') {
                e.preventDefault();
                form.reset();
                
                // Ręczne czyszczenie kontrolek wejściowych i rozwijanych list
                form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
                form.querySelectorAll('input[type="text"]').forEach(input => input.value = '');

                const baseUrl = window.location.pathname;
                window.history.pushState({}, '', baseUrl);
                fetchRecipes(baseUrl);
            }
        });
    }

    // 2. Obsługa kliknięć w przyciski paginacji (strony 1, 2, Następna itp.)
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

    // 3. Obsługa natywnych strzałek nawigacyjnych przeglądarki (wstecz i dalej w historii)
    window.addEventListener('popstate', function() {
        fetchRecipes(window.location.href);
    });
});
</script>
@endsection