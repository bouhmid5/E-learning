@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Éligibilité au certificat</h1>
        <p>{{ $inscription->cours->titre }}</p>
    </section>

    @error('certificate')
        <p class="field-error">{{ $message }}</p>
    @enderror

    <section class="course-card">
        <h2>{{ $result['eligible'] ? 'Éligible' : 'Non éligible' }}</h2>
        <dl>
            <div><dt>Progression complète</dt><dd>{{ $result['checks']['progression_complete'] ? 'Oui' : 'Non' }}</dd></div>
            <div><dt>Inscription active</dt><dd>{{ $result['checks']['not_abandoned'] ? 'Oui' : 'Non' }}</dd></div>
            <div><dt>Conditions validées</dt><dd>{{ $result['checks']['conditions_validated'] ? 'Oui' : 'Non' }}</dd></div>
            <div><dt>Évaluations réussies</dt><dd>{{ $result['checks']['evaluations_passed'] ? 'Oui' : 'Non' }}</dd></div>
        </dl>

        @if ($result['eligible'])
            <form method="POST" action="{{ route('candidate.enrollments.certificate.generate', $inscription) }}">
                @csrf
                <button type="submit">Générer le certificat</button>
            </form>
        @endif
    </section>
@endsection

