@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Utilisateurs</h1>
    </section>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <section>
        @foreach ($users as $user)
            <article class="course-card">
                <h2>{{ $user->prenom }} {{ $user->nom }}</h2>
                <p>{{ $user->email }} · {{ $user->statut->value }}</p>
                <form method="POST" action="{{ route('admin.users.status', $user) }}">
                    @csrf
                    @method('PATCH')
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
                </form>
            </article>
        @endforeach
    </section>

    {{ $users->links() }}
@endsection
