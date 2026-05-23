@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Modifier une categorie</h1>
    </section>

    <form method="POST" action="{{ route('admin.categories.update', $categorie) }}">
        @csrf
        @method('PUT')
        @include('admin.categories.partials.form')
        <button type="submit">Enregistrer</button>
    </form>
@endsection
