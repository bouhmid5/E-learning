@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>{{ $inscription->cours->titre }}</h1>
        <p>Progression: {{ number_format((float) $inscription->progression, 2) }}%</p>
    </section>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    @php
        $completed = $inscription->progressionsLecons->where('terminee', true)->pluck('lecon_id')->all();
    @endphp

    <section>
        @foreach ($inscription->cours->lecons->sortBy('ordre') as $lecon)
            <article class="course-card">
                <h2>{{ $lecon->ordre }}. {{ $lecon->titre }}</h2>
                <p>{{ $lecon->description }}</p>
                <p>{{ in_array($lecon->id, $completed, true) ? 'Terminee' : 'En cours' }}</p>
                <form method="POST" action="{{ route('candidate.enrollments.lessons.complete', [$inscription, $lecon]) }}">
                    @csrf
                    <button type="submit">Marquer comme terminee</button>
                </form>

                @if ($lecon->ressources->isNotEmpty())
                    <h3>Ressources</h3>
                    @foreach ($lecon->ressources->sortBy('ordre') as $ressource)
                        <p>
                            {{ $ressource->titre }}
                            @if ($ressource->telechargeable)
                                <a href="{{ route('candidate.enrollments.resources.download', [$inscription, $ressource]) }}">Telecharger</a>
                            @endif
                        </p>
                    @endforeach
                @endif
            </article>
        @endforeach
    </section>
@endsection
