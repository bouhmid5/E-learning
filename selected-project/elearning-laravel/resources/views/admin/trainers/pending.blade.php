@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Validation des formateurs</h1>
    </section>

    <section>
        <h2>Comptes en attente</h2>
        @forelse ($trainers as $formateur)
            <article class="course-card">
                <h3>{{ $formateur->utilisateur?->prenom }} {{ $formateur->utilisateur?->nom }}</h3>
                <p>{{ $formateur->utilisateur?->email }} - {{ $formateur->specialite }}</p>
                <form method="POST" action="{{ route('admin.trainers.validate', $formateur) }}" data-confirm="Valider ce formateur ?">
                    @csrf
                    <button type="submit">Valider</button>
                </form>
                <form method="POST" action="{{ route('admin.trainers.reject', $formateur) }}" data-confirm="Rejeter ce formateur ?">
                    @csrf
                    <textarea name="reason" required placeholder="Motif du rejet"></textarea>
                    <button type="submit">Rejeter</button>
                </form>
            </article>
        @empty
            <p>Aucun formateur en attente.</p>
        @endforelse
    </section>

    {{ $trainers->links() }}

    <section>
        <h2>Justificatifs en attente</h2>
        @forelse ($justificatifs as $justificatif)
            <article class="course-card">
                <h3>{{ $justificatif->type }}</h3>
                <p>{{ $justificatif->formateur?->utilisateur?->email }} - {{ $justificatif->fichier_url }}</p>
                <form method="POST" action="{{ route('admin.justificatifs.validate', $justificatif) }}" data-confirm="Valider ce justificatif ?">
                    @csrf
                    <button type="submit">Valider</button>
                </form>
                <form method="POST" action="{{ route('admin.justificatifs.reject', $justificatif) }}" data-confirm="Rejeter ce justificatif ?">
                    @csrf
                    <textarea name="reason" required placeholder="Motif du rejet"></textarea>
                    <button type="submit">Rejeter</button>
                </form>
            </article>
        @empty
            <p>Aucun justificatif en attente.</p>
        @endforelse
    </section>
@endsection
