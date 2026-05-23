@extends('layouts.app')

@section('content')
    <section class="auth-panel">
        <h1>Modifier le cours</h1>
        <form method="POST" action="{{ route('trainer.courses.update', $cours) }}">
            @csrf
            @method('PUT')
            @include('trainer.courses.partials.form', ['cours' => $cours])
            <button type="submit">Enregistrer</button>
        </form>
    </section>
@endsection

