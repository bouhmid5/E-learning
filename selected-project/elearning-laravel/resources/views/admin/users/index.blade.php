@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Administration</p>
        <h1>Utilisateurs</h1>
        <p>Consultez et ajustez les statuts des comptes utilisateur.</p>
    </section>

    @if ($users->isEmpty())
        <section class="empty-state">
            <h2>Aucun utilisateur</h2>
            <p>Les comptes apparaitront ici apres inscription ou creation via les seeders.</p>
        </section>
    @else
        <section class="table-list">
            @foreach ($users as $user)
                <article class="course-card">
                    <div class="card-header-line">
                        <div>
                            <h2>{{ $user->prenom }} {{ $user->nom }}</h2>
                            <p>{{ $user->email }}</p>
                        </div>
                        <span class="badge">{{ $user->statut->value }}</span>
                    </div>
                    <form method="POST" action="{{ route('admin.users.status', $user) }}" data-confirm="Modifier le statut de cet utilisateur ?">
                        @csrf
                        @method('PATCH')
                        <div class="filter-bar">
                            <label>
                                Statut
                                <select name="statut">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}" @selected($user->statut === $status)>
                                            {{ $status->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>
                            <button type="submit">Mettre a jour</button>
                        </div>
                    </form>
                </article>
            @endforeach
        </section>

        {{ $users->links() }}
    @endif
@endsection
