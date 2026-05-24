@extends('layouts.app')

@section('title', 'Modifier l evaluation')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Evaluations</p>
        <h1>Modifier l'evaluation</h1>
        <p>Configurez les questions, les options et les criteres de correction.</p>
    </section>

    <section class="form-card">
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
            <input type="text" name="enonce" placeholder="Enonce" required>
            <select name="type">
                @foreach (\App\Enums\TypeQuestion::cases() as $type)
                    <option value="{{ $type->value }}">{{ $type->value }}</option>
                @endforeach
            </select>
            <input type="number" name="points" value="1" min="0" step="0.01" required>
            <input type="text" name="valeur_attendue" placeholder="Valeur attendue">
            <input type="number" name="tolerance" placeholder="Tolerance" min="0" step="0.01">
            <input type="text" name="critere_description" placeholder="Critere de correction">
            <button type="submit">Ajouter</button>
        </form>
    </section>

    <section class="learning-list">
        <h2>Questions</h2>
        @forelse ($evaluation->questions as $question)
            <article class="course-card question-card">
                <div class="card-header-line">
                    <h3>{{ $question->enonce }}</h3>
                    <span class="badge">{{ $question->type->value }} - {{ $question->points }} point(s)</span>
                </div>

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
                    <input type="number" name="tolerance" value="{{ $question->criteresCorrection->first()?->tolerance }}" placeholder="Tolerance" min="0" step="0.01">
                    <input type="text" name="critere_description" value="{{ $question->criteresCorrection->first()?->description }}" placeholder="Critere">
                    <button type="submit">Mettre a jour</button>
                </form>

                <form method="POST" action="{{ route('trainer.questions.destroy', $question) }}" data-confirm="Supprimer cette question ?">
                    @csrf
                    @method('DELETE')
                    <button class="danger-button" type="submit">Supprimer</button>
                </form>

                <h4>Options</h4>
                <div class="resource-list">
                    @foreach ($question->optionsReponse as $option)
                        <div class="resource-item">
                            <form method="POST" action="{{ route('trainer.options.update', $option) }}" class="filter-bar">
                                @csrf
                                @method('PUT')
                                <input type="text" name="texte" value="{{ $option->texte }}" required>
                                <label class="checkbox-line">
                                    <input type="checkbox" name="est_correcte" value="1" @checked($option->est_correcte)>
                                    Correcte
                                </label>
                                <button type="submit">Modifier</button>
                            </form>
                            <form method="POST" action="{{ route('trainer.options.destroy', $option) }}" data-confirm="Supprimer cette option ?">
                                @csrf
                                @method('DELETE')
                                <button class="danger-button" type="submit">Supprimer</button>
                            </form>
                        </div>
                    @endforeach
                </div>

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
            <section class="empty-state">
                <h2>Aucune question</h2>
                <p>Ajoutez une premiere question pour construire cette evaluation.</p>
            </section>
        @endforelse
    </section>
@endsection
