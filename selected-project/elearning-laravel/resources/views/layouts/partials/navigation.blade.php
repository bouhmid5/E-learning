@php
    $webUser = auth()->user();
    $adminUser = auth('admin')->user();
@endphp

<nav class="main-nav" aria-label="Navigation principale">
    <a href="{{ route('home') }}" class="brand">{{ config('app.name', 'E-learning Platform') }}</a>

    <div class="nav-links">
        <a href="{{ route('courses.index') }}" @class(['active' => request()->routeIs('courses.*')])>Catalogue</a>
        <a href="{{ route('categories.index') }}" @class(['active' => request()->routeIs('categories.*')])>Categories</a>

        @if ($webUser?->candidat)
            <a href="{{ route('candidate.dashboard') }}" @class(['active' => request()->routeIs('candidate.dashboard')])>Candidat</a>
            <a href="{{ route('candidate.enrollments.index') }}" @class(['active' => request()->routeIs('candidate.enrollments.*')])>Mes cours</a>
            <a href="{{ route('candidate.results') }}" @class(['active' => request()->routeIs('candidate.results')])>Resultats</a>
            <a href="{{ route('candidate.certificates.index') }}" @class(['active' => request()->routeIs('candidate.certificates.*')])>Certificats</a>
        @endif

        @if ($webUser?->formateur)
            <a href="{{ route('trainer.dashboard') }}" @class(['active' => request()->routeIs('trainer.dashboard')])>Formateur</a>
            <a href="{{ route('trainer.courses.index') }}" @class(['active' => request()->routeIs('trainer.courses.*')])>Mes cours</a>
        @endif

        @if ($adminUser)
            <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Admin</a>
            <a href="{{ route('admin.users.index') }}" @class(['active' => request()->routeIs('admin.users.*')])>Utilisateurs</a>
            <a href="{{ route('admin.courses.pending') }}" @class(['active' => request()->routeIs('admin.courses.*')])>Validation cours</a>
        @endif
    </div>

    <div class="nav-actions">
        @if ($webUser || $adminUser)
            <span class="nav-user">{{ $webUser?->prenom ?? $adminUser?->prenom }} {{ $webUser?->nom ?? $adminUser?->nom }}</span>
            <form method="POST" action="{{ route('logout') }}" data-confirm="Voulez-vous vraiment vous deconnecter ?">
                @csrf
                <button type="submit" class="link-button">Deconnexion</button>
            </form>
        @else
            <a href="{{ route('login') }}">Connexion</a>
            <a href="{{ route('register.candidate') }}" class="button-link">Inscription</a>
        @endif
    </div>
</nav>
