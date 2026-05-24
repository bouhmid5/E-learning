<?php

namespace Tests\Feature;

use App\Enums\StatutCompte;
use App\Enums\StatutCours;
use App\Models\Administrateur;
use App\Models\Candidat;
use App\Models\Cours;
use App\Models\Formateur;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForminiFrontendSliceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_auth_pages_load_with_formini_branding(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Formini')
            ->assertSee('Connexion');

        $this->get(route('register.candidate'))
            ->assertOk()
            ->assertSee('Formini')
            ->assertSee('Inscription candidat');

        $this->get(route('register.trainer'))
            ->assertOk()
            ->assertSee('Formini')
            ->assertSee('Inscription formateur');
    }

    public function test_public_catalogue_loads_and_only_exposes_published_courses(): void
    {
        Cours::factory()->create([
            'titre' => 'Cours Formini publie',
            'statut' => StatutCours::PUBLIE,
        ]);
        Cours::factory()->create([
            'titre' => 'Cours Formini brouillon',
            'statut' => StatutCours::BROUILLON,
        ]);

        $this->get(route('courses.index'))
            ->assertOk()
            ->assertSee('Catalogue Formini')
            ->assertSee('Cours Formini publie')
            ->assertDontSee('Cours Formini brouillon');
    }

    public function test_published_course_details_page_loads(): void
    {
        $cours = Cours::factory()->create([
            'titre' => 'Laravel avec Formini',
            'statut' => StatutCours::PUBLIE,
        ]);

        $this->get(route('courses.show', $cours))
            ->assertOk()
            ->assertSee('Laravel avec Formini')
            ->assertSee("Se connecter pour s'inscrire", false);
    }

    public function test_unpublished_course_details_are_not_public(): void
    {
        $cours = Cours::factory()->create(['statut' => StatutCours::REJETE]);

        $this->get(route('courses.show', $cours))
            ->assertNotFound();
    }

    public function test_candidate_dashboard_is_role_protected(): void
    {
        $candidateUser = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);
        Candidat::factory()->create(['utilisateur_id' => $candidateUser->id]);

        $trainerUser = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);
        Formateur::factory()->create([
            'utilisateur_id' => $trainerUser->id,
            'statut_validation' => StatutCompte::ACTIF,
        ]);

        $this->actingAs($candidateUser, 'web')
            ->get(route('candidate.dashboard'))
            ->assertOk()
            ->assertSee('Tableau de bord Formini');

        $this->actingAs($trainerUser, 'web')
            ->get(route('candidate.dashboard'))
            ->assertForbidden();
    }

    public function test_trainer_dashboard_is_role_protected(): void
    {
        $trainerUser = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);
        Formateur::factory()->create([
            'utilisateur_id' => $trainerUser->id,
            'statut_validation' => StatutCompte::ACTIF,
        ]);

        $candidateUser = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);
        Candidat::factory()->create(['utilisateur_id' => $candidateUser->id]);

        $this->actingAs($trainerUser, 'web')
            ->get(route('trainer.dashboard'))
            ->assertOk()
            ->assertSee('Tableau de bord Formini');

        $this->actingAs($candidateUser, 'web')
            ->get(route('trainer.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_dashboard_is_available_to_admins_only(): void
    {
        $administrateur = Administrateur::factory()->create(['statut' => StatutCompte::ACTIF]);
        $candidateUser = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);
        Candidat::factory()->create(['utilisateur_id' => $candidateUser->id]);

        $this->actingAs($administrateur, 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Tableau de bord Formini');

        $this->actingAs($candidateUser, 'web')
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }
}
