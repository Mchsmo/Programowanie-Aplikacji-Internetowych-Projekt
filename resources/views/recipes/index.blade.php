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
        container.style.opacity = '0.5';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Błąd połączenia z serwerem');
            
            return response.text();
        })
        .then(html => {
            container.innerHTML = html;
            container.style.opacity = '1';
            
            container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        })
        .catch(error => {
            console.error('Błąd:', error);
            container.style.opacity = '1';
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const searchParams = new URLSearchParams(formData);
            const targetUrl = form.getAttribute('action') + '?' + searchParams.toString();

            window.history.pushState({}, '', targetUrl);
            fetchRecipes(targetUrl);
        });

        form.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('button') && e.target.innerText === 'Reset') {
                e.preventDefault();
                form.reset();
                
                form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
                form.querySelectorAll('input[type="text"]').forEach(input => input.value = '');

                const baseUrl = window.location.pathname;
                window.history.pushState({}, '', baseUrl);
                fetchRecipes(baseUrl);
            }
        });
    }

    if (container) {
        container.addEventListener('click', function(e) {
            const anchor = e.target.closest('a');
            
            if (anchor && (anchor.closest('.pagination') || anchor.closest('nav'))) {
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