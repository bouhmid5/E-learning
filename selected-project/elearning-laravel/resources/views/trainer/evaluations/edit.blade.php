@extends('layouts.app')

@section('content')
    <section class="auth-panel">
        <h1>Modifier l'évaluation</h1>
        <form method="POST" action="{{ route('trainer.evaluations.update', $evaluation) }}">
            @csrf
            @method('PUT')
            @include('trainer.evaluations.partials.form', ['evaluation' => $evaluation])
            <button type="submit">Enregistrer</button>
        </form>
    </section>

    <section class="page-heading">
        <h2>Ajouter une question</h2>
        <form method="POST" action="{{ route('trainer.evaluations.questions.store', $evaluation) }}" class="filter-bar">
            @csrf
            <input type="text" name="enonce" placeholder="Énoncé" required>
            <select name="type">
                @foreach (\App\Enums\TypeQuestion::cases() as $type)
                    <option value="{{ $type->value }}">{{ $type->value }}</option>
                @endforeach
            </select>
            <input type="number" name="points" value="1" min="0" step="0.01" required>
            <input type="text" name="valeur_attendue" placeholder="Valeur attendue">
            <input type="number" name="tolerance" placeholder="Tolérance" min="0" step="0.01">
            <input type="text" name="critere_description" placeholder="Critère de correction">
            <button type="submit">Ajouter</button>
        </form>
    </section>

    <section>
        <h2>Questions</h2>
        @forelse ($evaluation->questions as $question)
            <article class="course-card">
                <h3>{{ $question->enonce }}</h3>
                <p>{{ $question->type->value }} · {{ $question->points }} point(s)</p>

                <form method="POST" action="{{ route('trainer.questions.update', $question) }}" class="filter-bar">
                    @csrf
                    @method('PUT')
                    <input type="text" name="enonce" value="{{ $question->enonce }}" required>
                    <select name="type">
                        @foreach (\App\Enums\TypeQuestion::cases() as $type)
                            <option value="{{ $type->value }}" @selected($question->type === $type)>{{ $type->value }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="points" value="{{ $question->points }}" min="0" step="0.01" required>
                    <input type="text" name="valeur_attendue" value="{{ $question->criteresCorrection->first()?->valeur_attendue }}" placeholder="Valeur attendue">
                    <input type="number" name="tolerance" value="{{ $question->criteresCorrection->first()?->tolerance }}" placeholder="Tolérance" min="0" step="0.01">
                    <input type="text" name="critere_description" value="{{ $question->criteresCorrection->first()?->description }}" placeholder="Critère">
                    <button type="submit">Mettre à jour</button>
                </form>

                <form method="POST" action="{{ route('trainer.questions.destroy', $question) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Supprimer</button>
                </form>

                <h4>Options</h4>
                @foreach ($question->optionsReponse as $option)
                    <form method="POST" action="{{ route('trainer.options.update', $option) }}" class="filter-bar">
                        @csrf
                        @method('PUT')
                        <input type="text" name="texte" value="{{ $option->texte }}" required>
                        <label class="checkbox-line">
                            <input type="checkbox" name="est_correcte" value="1" @checked($option->est_correcte)>
                            Correcte
                        </label>
                        <button type="submit">Modifier option</button>
                    </form>
                @endforeach

                <form method="POST" action="{{ route('trainer.questions.options.store', $question) }}" class="filter-bar">
                    @csrf
                    <input type="text" name="texte" placeholder="Nouvelle option" required>
                    <label class="checkbox-line">
                        <input type="checkbox" name="est_correcte" value="1">
                        Correcte
                    </label>
                    <button type="submit">Ajouter option</button>
                </form>
            </article>
        @empty
            <p>Aucune question.</p>
        @endforelse
    </section>
@endsection

