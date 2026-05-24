@extends('layouts.app')

@section('title', 'Panel Moderacji Treści')

@section('content')
<section id="main">
    <div class="container">
        
        <header>
            <h2 style="margin-bottom: 1em; text-align: center;">Panel Moderacji Treści</h2>
        </header>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1em; margin-bottom: 1.5em; border-radius: 4px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        <style>
            .mod-tabs { display: flex; list-style: none; padding: 0; margin: 0 0 1.5em 0; border-bottom: 2px solid #e5e5e5; }
            .mod-tabs li { margin-right: 0.5em; }
            .mod-tabs a { display: block; padding: 0.75em 1.5em; text-decoration: none; border: 2px solid transparent; border-bottom: none; border-radius: 4px 4px 0 0; font-weight: 600; color: #777; }
            .mod-tabs a.active { background: #fff; border-color: #e5e5e5; color: #ed786a; border-bottom-color: #fff; margin-bottom: -2px; }
            
            .tab-content { display: none; }
            .tab-content.active { display: block; }

            .table-wrapper table {
                width: 100%;
                border-collapse: collapse;
                margin: 1em 0;
                background: #ffffff !important; 
                color: #333333 !important;      
                border: 1px solid #e5e5e5 !important;
            }

            .table-wrapper table th {
                background-color: #f8f9fa !important; 
                color: #222222 !important;            
                font-weight: 700 !important;
                padding: 12px 16px !important;
                border-bottom: 2px solid #ed786a !important;
                text-align: left;
            }

            .table-wrapper table td {
                padding: 12px 16px !important;
                border-bottom: 1px solid #eeeeee !important;
                color: #444444 !important;          
                vertical-align: middle !important;
                background: #ffffff !important;       
            }

            .table-wrapper table a {
                color: #ed786a !important;
                text-decoration: underline;
            }
            .table-wrapper table a:hover {
                color: #333333 !important;
            }
        </style>

        <ul class="mod-tabs">
            <li><a href="#recipes" class="tab-link active" onclick="switchTab(event, 'recipes')">Przepisy ({{ $recipes->total() }})</a></li>
            <li><a href="#comments" class="tab-link" onclick="switchTab(event, 'comments')">Komentarze ({{ $comments->total() }})</a></li>
            <li><a href="#users" class="tab-link" onclick="switchTab(event, 'users')">Użytkownicy ({{ $users->total() }})</a></li>
        </ul>

        <div id="recipes" class="tab-content active">
            @if($recipes->isEmpty())
                <p>Brak przepisów do moderacji.</p>
            @else
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Tytuł</th>
                                <th>Autor</th>
                                <th>Data dodania</th>
                                <th style="text-align: right;">Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recipes as $recipe)
                                <tr>
                                    <td>
                                        <a href="{{ route('recipes.show', $recipe->id_recipe) }}" target="_blank"><strong>{{ $recipe->title }}</strong></a>
                                    </td>
                                    <td>{{ $recipe->user ? $recipe->user->name : 'Anonim' }}</td>
                                    <td>{{ $recipe->created_at->format('d.m.Y H:i') }}</td>
                                    <td style="text-align: right;">
                                        <form action="{{ route('moderation.recipes.destroy', $recipe->id_recipe) }}" method="POST" style="display:inline;" onsubmit="return confirm('Czy na pewno chcesz bezpowrotnie usunąć ten przepis?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button small" style="background:#e74c3c; box-shadow:none; color:#fff;">Usuń</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $recipes->fragment('recipes')->links('recipes.partials.pagination') }}
            @endif
        </div>

        <div id="comments" class="tab-content">
            @if($comments->isEmpty())
                <p>Brak komentarzy do moderacji.</p>
            @else
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Treść komentarza</th>
                                <th>Autor</th>
                                <th>Przepis</th>
                                <th>Data</th>
                                <th style="text-align: right;">Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comments as $comment)
                                <tr>
                                    <td>{{ Str::limit($comment->content, 60) }}</td>
                                    <td>{{ $comment->user ? $comment->user->name : 'Anonim' }}</td>
                                    <td>
                                        @if($comment->recipe)
                                            <a href="{{ route('recipes.show', $comment->recipe->id_recipe) }}" target="_blank">{{ Str::limit($comment->recipe->title, 30) }}</a>
                                        @else
                                            <span style="color: gray; font-style: italic;">[Usunięty przepis]</span>
                                        @endif
                                    </td>
                                    <td>{{ $comment->created_at->format('d.m.Y H:i') }}</td>
                                    <td style="text-align: right;">
                                        <form action="{{ route('moderation.comments.destroy', $comment->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć ten komentarz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button small" style="background:#e74c3c; box-shadow:none; color:#fff;">Usuń</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $comments->fragment('comments')->links('recipes.partials.pagination') }}
            @endif
        </div>

        <div id="users" class="tab-content">
            @if($users->isEmpty())
                <p>Brak innych użytkowników w bazie.</p>
            @else
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Nazwa użytkownika</th>
                                <th>Adres E-mail</th>
                                <th>Data rejestracji</th>
                                <th>Status</th>
                                <th style="text-align: right;">Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td><strong>{{ $user->name }}</strong></td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at ? $user->created_at->format('d.m.Y') : '-' }}</td>
                                    <td>
                                        @if($user->is_active)
                                            <span style="color: #27ae60; font-weight: bold;">Aktywny</span>
                                        @else
                                            <span style="color: #c0392b; font-weight: bold;">Zablokowany</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right;">
                                        <form action="{{ route('moderation.users.toggle', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @if($user->is_active)
                                                <button type="submit" class="button small alt" style="border-color:#e67e22; color:#e67e22;">Zablokuj</button>
                                            @else
                                                <button type="submit" class="button small" style="background:#2ecc71; box-shadow:none; color:#fff;">Odblokuj</button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $users->fragment('users')->links('recipes.partials.pagination') }}
            @endif
        </div>

    </div>
</section>

<script>
function switchTab(evt, tabId) {
    if(evt) evt.preventDefault();
    
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('active'));
    
    document.getElementById(tabId).classList.add('active');
    if(evt) evt.currentTarget.classList.add('active');
    
    window.location.hash = tabId;
}

// Obsługa zapamiętania aktywnej karty po przeładowaniu / paginacji
document.addEventListener("DOMContentLoaded", function() {
    const hash = window.location.hash;
    if (hash && document.getElementById(hash.replace('#', ''))) {
        const tabTarget = hash.replace('#', '');
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('active'));
        
        document.getElementById(tabTarget).classList.add('active');
        const activeLink = document.querySelector(`.mod-tabs a[href="${hash}"]`);
        if(activeLink) activeLink.classList.add('active');
    }
});
</script>
@endsection