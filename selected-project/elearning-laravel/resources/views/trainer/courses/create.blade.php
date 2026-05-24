@extends('layouts.app')

@section('title', 'Creer un cours')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Studio formateur</p>
        <h1>Creer un cours</h1>
        <p>Le cours est cree en brouillon avant ajout des lecons et soumission.</p>
    </section>

    <section class="form-card">
        <form method="POST" action="{{ route('trainer.courses.store') }}">
            @csrf
            @include('trainer.courses.partials.form', ['cours' => null])
            <button type="submit">Creer le brouillon</button>
        </form>
    </section>
@endsection
