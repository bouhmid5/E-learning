@php
    $webUser = auth()->user();
    $adminUser = auth('admin')->user();
@endphp

<nav class="main-nav" aria-label="Navigation principale">
    <a href="{{ route('home') }}" class="brand" aria-label="Formini">
        <x-application-logo />
    </a>

    <div class="nav-links">
        @if (! $webUser && ! $adminUser)
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Accueil</a>
            <a href="{{ route('courses.index') }}" @class(['active' => request()->routeIs('courses.*')])>Catalogue</a>
        @endif

        @if ($webUser?->candidat)
            <a href="{{ route('candidate.dashboard') }}" @class(['active' => request()->routeIs('candidate.dashboard')])>Tableau de bord</a>
            <a href="{{ route('candidate.enrollments.index') }}" @class(['active' => request()->routeIs('candidate.enrollments.*')])>Mes cours</a>
            <a href="{{ route('courses.index') }}" @class(['active' => request()->routeIs('courses.*')])>Catalogue</a>
            <a href="{{ route('candidate.results') }}" @class(['active' => request()->routeIs('candidate.results')])>Resultats</a>
            <a href="{{ route('candidate.certificates.index') }}" @class(['active' => request()->routeIs('candidate.certificates.*')])>Certificats</a>
            <span class="nav-disabled" title="Route profil non disponible">Profil</span>
        @endif

        @if ($webUser?->formateur)
            <a href="{{ route('trainer.dashboard') }}" @class(['active' => request()->routeIs('trainer.dashboard')])>Tableau de bord</a>
            <a href="{{ route('trainer.courses.index') }}" @class(['active' => request()->routeIs('trainer.courses.*')])>Mes cours</a>
            <span class="nav-disabled" title="Choisissez un cours pour gerer ses evaluations">Evaluations</span>
            <span class="nav-disabled" title="Route performances non disponible">Performances</span>
            <span class="nav-disabled" title="Route messages non disponible">Messages</span>
            <span class="nav-disabled" title="Route profil non disponible">Profil</span>
        @endif

        @if ($adminUser)
            <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Tableau de bord</a>
            <a href="{{ route('admin.users.index') }}" @class(['active' => request()->routeIs('admin.users.*')])>Utilisateurs</a>
            <a href="{{ route('admin.trainers.pending') }}" @class(['active' => request()->routeIs('admin.trainers.*')])>Formateurs</a>
            <a href="{{ route('admin.courses.pending') }}" @class(['active' => request()->routeIs('admin.courses.*')])>Cours a valider</a>
            <a href="{{ route('admin.categories.index') }}" @class(['active' => request()->routeIs('admin.categories.*')])>Categories</a>
            <span class="nav-disabled" title="Route statistiques non disponible">Statistiques</span>
            <span class="nav-disabled" title="Route support non disponible">Support</span>
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
            <a href="{{ route('register.candidate') }}" class="button-link">Creer un compte</a>
        @endif
    </div>
</nav>
