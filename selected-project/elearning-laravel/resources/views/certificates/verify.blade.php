@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Vérification de certificat</h1>
    </section>

    @if (! $certificat)
        <section class="empty-state">
            <h2>Certificat introuvable</h2>
            <p>Aucun certificat ne correspond au code {{ $codeVerification }}.</p>
        </section>
    @elseif (! $certificat->actif)
        <section class="empty-state">
            <h2>Certificat inactif</h2>
            <p>Le certificat {{ $certificat->code_verification }} existe mais n'est pas actif.</p>
        </section>
    @else
        <section class="course-card">
            <h2>Certificat valide</h2>
            <dl>
                <div><dt>Code</dt><dd>{{ $certificat->code_verification }}</dd></div>
                <div><dt>Candidat</dt><dd>{{ $certificat->inscription->candidat->utilisateur->prenom }} {{ $certificat->inscription->candidat->utilisateur->nom }}</dd></div>
                <div><dt>Cours</dt><dd>{{ $certificat->inscription->cours->titre }}</dd></div>
                <div><dt>Date</dt><dd>{{ $certificat->date_generation?->format('d/m/Y') }}</dd></div>
            </dl>
        </section>
    @endif
@endsection

