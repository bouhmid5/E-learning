@extends('layouts.app')

@section('title', 'Validation formateurs')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Validation</p>
        <h1>Validation des formateurs</h1>
        <p>Controlez les demandes de formateurs et leurs justificatifs avant activation.</p>
    </section>

    <section class="learning-list">
        <h2>Comptes en attente</h2>
        @forelse ($trainers as $formateur)
            <article class="course-card">
                <div class="card-header-line">
                    <div>
                        <h3>{{ $formateur->utilisateur?->prenom }} {{ $formateur->utilisateur?->nom }}</h3>
                        <p>{{ $formateur->utilisateur?->email }} - {{ $formateur->specialite }}</p>
                    </div>
                    <span class="badge badge-warning">{{ $formateur->statut_validation->value }}</span>
                </div>
                <div class="inline-actions">
                    <form method="POST" action="{{ route('admin.trainers.validate', $formateur) }}" data-confirm="Valider ce formateur ?">
                        @csrf
                        <button type="submit">Valider</button>
                    </form>
                    <form method="POST" action="{{ route('admin.trainers.reject', $formateur) }}" data-confirm="Rejeter ce formateur ?">
                        @csrf
                        <textarea name="reason" required placeholder="Motif du rejet"></textarea>
                        <button class="danger-button" type="submit">Rejeter</button>
                    </form>
                </div>
            </article>
        @empty
            <section class="empty-state">
                <h2>Aucun formateur en attente</h2>
                <p>Les nouvelles demandes apparaitront ici.</p>
            </section>
        @endforelse

        {{ $trainers->links() }}

        <h2>Justificatifs en attente</h2>
        @forelse ($justificatifs as $justificatif)
            <article class="course-card">
                <div class="card-header-line">
                    <div>
                        <h3>{{ $justificatif->type }}</h3>
                        <p>{{ $justificatif->formateur?->utilisateur?->email }} - {{ $justificatif->fichier_url }}</p>
                    </div>
                    <span class="badge badge-warning">{{ $justificatif->statut->value }}</span>
                </div>
                <div class="inline-actions">
                    <form method="POST" action="{{ route('admin.justificatifs.validate', $justificatif) }}" data-confirm="Valider ce justificatif ?">
                        @csrf
                        <button type="submit">Valider</button>
                    </form>
                    <form method="POST" action="{{ route('admin.justificatifs.reject', $justificatif) }}" data-confirm="Rejeter ce justificatif ?">
                        @csrf
                        <textarea name="reason" required placeholder="Motif du rejet"></textarea>
                        <button class="danger-button" type="submit">Rejeter</button>
                    </form>
                </div>
            </article>
        @empty
            <section class="empty-state">
                <h2>Aucun justificatif en attente</h2>
                <p>Les justificatifs a controler apparaitront ici.</p>
            </section>
        @endforelse
    </section>
@endsection
