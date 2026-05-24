@extends('layouts.app')

@section('title', 'Creer une evaluation')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Evaluations</p>
        <h1>Creer une evaluation</h1>
        <p>{{ $cours->titre }}</p>
    </section>

    <section class="form-card">
        <form method="POST" action="{{ route('trainer.courses.evaluations.store', $cours) }}">
            @csrf
            @include('trainer.evaluations.partials.form', ['evaluation' => null])
            <button type="submit">Creer</button>
        </form>
    </section>
@endsection
