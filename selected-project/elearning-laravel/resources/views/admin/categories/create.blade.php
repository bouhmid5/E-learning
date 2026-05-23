@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Creer une categorie</h1>
    </section>

    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        @include('admin.categories.partials.form')
        <button type="submit">Creer</button>
    </form>
@endsection
