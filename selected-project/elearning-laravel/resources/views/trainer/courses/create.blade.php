@extends('layouts.app')

@section('content')
    <section class="auth-panel">
        <h1>Créer un cours</h1>
        <form method="POST" action="{{ route('trainer.courses.store') }}">
            @csrf
            @include('trainer.courses.partials.form', ['cours' => null])
            <button type="submit">Créer le brouillon</button>
        </form>
    </section>
@endsection

