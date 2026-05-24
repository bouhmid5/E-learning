@extends('layouts.app')

@section('title', $evaluation->titre)

@section('content')
    <section class="course-detail">
        <div class="detail-hero">
            <div>
                <p class="eyebrow">Evaluation</p>
                <h1>{{ $evaluation->titre }}</h1>
                <p>{{ $evaluation->description }}</p>
            </div>
            <span class="badge">{{ $evaluation->type_evaluation->value }}</span>
        </div>

        <form method="POST" action="{{ route('candidate.evaluations.submit', $evaluation) }}">
            @csrf

            <div class="learning-list">
                @foreach ($evaluation->questions as $question)
                    <article class="course-card question-card">
                        <div class="card-header-line">
                            <h2>{{ $question->enonce }}</h2>
                            <span class="badge">{{ $question->points }} point(s)</span>
                        </div>

                        @if (in_array($question->type, [\App\Enums\TypeQuestion::QCM, \App\Enums\TypeQuestion::VRAI_FAUX], true))
                            @foreach ($question->optionsReponse as $option)
                                <label class="checkbox-line">
                                    @if ($question->type === \App\Enums\TypeQuestion::QCM)
                                        <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option->id }}">
                                    @else
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}">
                                    @endif
                                    {{ $option->texte }}
                                </label>
                            @endforeach
                        @else
                            <input type="text" name="answers[{{ $question->id }}]" placeholder="Votre reponse">
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="hero-actions">
                <button type="submit" data-loading-text="Soumission...">Soumettre</button>
            </div>
        </form>
    </section>
@endsection
