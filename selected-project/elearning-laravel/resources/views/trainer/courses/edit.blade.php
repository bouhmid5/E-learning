@extends('layouts.app')

@section('title', 'Modifier le cours')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Studio formateur</p>
        <h1>Modifier le cours</h1>
        <p>Vous pouvez modifier les brouillons et les cours rejetes avant nouvelle soumission.</p>
    </section>

    <section class="form-card">
        <form method="POST" action="{{ route('trainer.courses.update', $cours) }}">
            @csrf
            @method('PUT')
            @include('trainer.courses.partials.form', ['cours' => $cours])
            <button type="submit">Enregistrer</button>
        </form>
    </section>
@endsection
