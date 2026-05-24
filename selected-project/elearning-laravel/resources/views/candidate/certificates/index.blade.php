@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Mes certificats</h1>
    </section>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    @forelse ($certificats as $certificat)
        <article class="course-card">
            <h2>{{ $certificat->inscription->cours->titre }}</h2>
            <p>Code: {{ $certificat->code_verification }}</p>
            <p>Généré le {{ $certificat->date_generation?->format('d/m/Y') }}</p>
            <p>{{ $certificat->actif ? 'Actif' : 'Inactif' }}</p>

            @can('download', $certificat)
                <p><a href="{{ route('candidate.certificates.download', $certificat) }}">Télécharger</a></p>
            @endcan
        </article>
    @empty
        <section class="empty-state">
            <h2>Aucun certificat</h2>
            <p>Les certificats apparaîtront après validation des conditions de certification.</p>
        </section>
    @endforelse

    {{ $certificats->links() }}
@endsection

