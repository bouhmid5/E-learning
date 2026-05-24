@extends('layouts.app')

@section('content')
    <section class="auth-panel">
        <h1>Créer une évaluation</h1>
        <form method="POST" action="{{ route('trainer.courses.evaluations.store', $cours) }}">
            @csrf
            @include('trainer.evaluations.partials.form', ['evaluation' => null])
            <button type="submit">Créer</button>
        </form>
    </section>
@endsection

