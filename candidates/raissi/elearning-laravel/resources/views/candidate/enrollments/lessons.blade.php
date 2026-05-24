@extends('layouts.app')

@section('title', 'Lecons')

@section('content')
    @php
        $completed = $inscription->progressionsLecons->where('terminee', true)->pluck('lecon_id')->all();
        $progress = (float) $inscription->progression;
    @endphp

    <section class="page-heading split-heading">
        <div>
            <p class="eyebrow">Espace d'apprentissage</p>
            <h1>{{ $inscription->cours->titre }}</h1>
            <p>Progression: {{ number_format($progress, 2) }}%</p>
            <div class="progress-track" aria-label="Progression">
                <span class="progress-fill" style="width: {{ min(100, max(0, $progress)) }}%"></span>
            </div>
        </div>
        <a class="button-link" href="{{ route('candidate.enrollments.show', $inscription) }}">Resume du cours</a>
    </section>

    <section class="learning-list">
        @forelse ($inscription->cours->lecons->sortBy('ordre') as $lecon)
            @php($isCompleted = in_array($lecon->id, $completed, true))
            <article class="course-card">
                <div class="card-header-line">
                    <span class="badge {{ $isCompleted ? '' : 'badge-warning' }}">{{ $isCompleted ? 'Terminee' : 'En cours' }}</span>
                    <span>Lecon {{ $lecon->ordre }}</span>
                </div>
                <h2>{{ $lecon->titre }}</h2>
                <p>{{ $lecon->description }}</p>
                <form method="POST" action="{{ route('candidate.enrollments.lessons.complete', [$inscription, $lecon]) }}">
                    @csrf
                    <button type="submit" @disabled($isCompleted)>Marquer comme terminee</button>
                </form>

                @if ($lecon->ressources->isNotEmpty())
                    <div class="resource-list">
                        @foreach ($lecon->ressources->sortBy('ordre') as $ressource)
                            <div class="resource-item">
                                <span>{{ $ressource->titre }}</span>
                                @if ($ressource->telechargeable)
                                    <a href="{{ route('candidate.enrollments.resources.download', [$inscription, $ressource]) }}">Telecharger</a>
                                @else
                                    <span class="muted-link">Consultation limitee</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>
        @empty
            <section class="empty-state">
                <h2>Aucune lecon</h2>
                <p>Ce cours ne contient pas encore de lecons visibles.</p>
            </section>
        @endforelse
    </section>
@endsection
