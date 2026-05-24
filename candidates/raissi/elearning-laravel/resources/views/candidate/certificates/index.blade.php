@extends('layouts.app')

@section('title', 'Mes certificats')

@section('content')
    <section class="page-heading split-heading">
        <div>
            <p class="eyebrow">Certification</p>
            <h1>Mes certificats</h1>
            <p>Retrouvez les certificats generes apres validation des conditions de certification.</p>
        </div>
        <a class="button-link" href="{{ route('candidate.enrollments.index') }}">Mes cours</a>
    </section>

    @if ($certificats->isEmpty())
        <section class="empty-state">
            <h2>Aucun certificat</h2>
            <p>Les certificats apparaitront apres validation des conditions de certification.</p>
        </section>
    @else
        <section class="course-grid">
            @foreach ($certificats as $certificat)
                <article class="course-card">
                    <div class="card-header-line">
                        <span class="badge {{ $certificat->actif ? '' : 'badge-danger' }}">{{ $certificat->actif ? 'Actif' : 'Inactif' }}</span>
                        <span>{{ $certificat->date_generation?->format('d/m/Y') }}</span>
                    </div>
                    <h2>{{ $certificat->inscription->cours->titre }}</h2>
                    <dl class="certificate-meta">
                        <div><dt>Code</dt><dd>{{ $certificat->code_verification }}</dd></div>
                    </dl>

                    @can('download', $certificat)
                        <a class="card-action" href="{{ route('candidate.certificates.download', $certificat) }}">Telecharger</a>
                    @endcan
                </article>
            @endforeach
        </section>

        {{ $certificats->links() }}
    @endif
@endsection
