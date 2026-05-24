@extends('layouts.app')

@section('title', 'Modifier une categorie')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Administration</p>
        <h1>Modifier une categorie</h1>
        <p>Mettez a jour le nom, le parent ou la description de la categorie.</p>
    </section>

    <section class="form-card">
        <form method="POST" action="{{ route('admin.categories.update', $categorie) }}">
            @csrf
            @method('PUT')
            @include('admin.categories.partials.form')
            <button type="submit">Enregistrer</button>
        </form>
    </section>
@endsection
