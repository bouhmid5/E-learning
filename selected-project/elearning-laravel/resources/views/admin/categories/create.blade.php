@extends('layouts.app')

@section('title', 'Creer une categorie')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Administration</p>
        <h1>Creer une categorie</h1>
        <p>Structurez le catalogue pour faciliter la navigation publique.</p>
    </section>

    <section class="form-card">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            @include('admin.categories.partials.form')
            <button type="submit">Creer</button>
        </form>
    </section>
@endsection
