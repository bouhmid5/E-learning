@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Eligibilite au certificat</h1>
        <p>{{ $inscription->cours->titre }}</p>
    </section>

    @error('certificate')
        <p class="field-error">{{ $message }}</p>
    @enderror

    <section class="course-card">
        <h2>{{ $result['eligible'] ? 'Eligible' : 'Non eligible' }}</h2>
        <dl>
            <div><dt>Progression complete</dt><dd>{{ $result['checks']['progression_complete'] ? 'Oui' : 'Non' }}</dd></div>
            <div><dt>Inscription active</dt><dd>{{ $result['checks']['not_abandoned'] ? 'Oui' : 'Non' }}</dd></div>
            <div><dt>Conditions validees</dt><dd>{{ $result['checks']['conditions_validated'] ? 'Oui' : 'Non' }}</dd></div>
            <div><dt>Evaluations reussies</dt><dd>{{ $result['checks']['evaluations_passed'] ? 'Oui' : 'Non' }}</dd></div>
        </dl>

        @if ($result['eligible'])
            <form method="POST" action="{{ route('candidate.enrollments.certificate.generate', $inscription) }}">
                @csrf
                <button type="submit">Generer le certificat</button>
            </form>
        @endif
    </section>
@endsection
