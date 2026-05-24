@extends('layouts.app')

@section('content')
    <section class="course-detail">
        <h1>{{ $evaluation->titre }}</h1>
        <p>{{ $evaluation->description }}</p>

        <form method="POST" action="{{ route('candidate.evaluations.submit', $evaluation) }}">
            @csrf

            @foreach ($evaluation->questions as $question)
                <article class="course-card">
                    <h2>{{ $question->enonce }}</h2>
                    <p>{{ $question->points }} point(s)</p>

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
                        <input type="text" name="answers[{{ $question->id }}]" placeholder="Votre réponse">
                    @endif
                </article>
            @endforeach

            <button type="submit">Soumettre</button>
        </form>
    </section>
@endsection

