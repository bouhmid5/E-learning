<?php

namespace Tests\Feature;

use App\Enums\StatutCompte;
use App\Enums\StatutCours;
use App\Enums\StatutJustificatif;
use App\Models\Administrateur;
use App\Models\Categorie;
use App\Models\Cours;
use App\Models\Formateur;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminValidationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_admin_routes_require_admin_authentication(): void
    {
        $this->get('/admin/users')->assertRedirect('/login');

        $utilisateur = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);

        $this->actingAs($utilisateur, 'web')
            ->get('/admin/users')
            ->assertRedirect('/login');
    }

    public function test_admin_can_activate_and_deactivate_users(): void
    {
        $admin = $this->admin();
        $user = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);

        $this->actingAs($admin, 'admin')
            ->patch("/admin/users/{$user->id}/status", ['statut' => StatutCompte::DESACTIVE->value])
            ->assertRedirect();

        $this->assertDatabaseHas('utilisateurs', [
            'id' => $user->id,
            'statut' => StatutCompte::DESACTIVE->value,
        ]);
    }

    public function test_admin_can_validate_trainer_account(): void
    {
        $admin = $this->admin();
        [$user, $formateur] = $this->pendingTrainer();

        $this->actingAs($admin, 'admin')
            ->post("/admin/trainers/{$formateur->id}/validate")
            ->assertRedirect();

        $this->assertDatabaseHas('formateurs', [
            'id' => $formateur->id,
            'administrateur_validateur_id' => $admin->id,
            'statut_validation' => StatutCompte::ACTIF->value,
        ]);
        $this->assertDatabaseHas('utilisateurs', [
            'id' => $user->id,
            'statut' => StatutCompte::ACTIF->value,
        ]);
    }

    public function test_trainer_rejection_requires_reason_and_rejects_account(): void
    {
        $admin = $this->admin();
        [$user, $formateur] = $this->pendingTrainer();

        $this->actingAs($admin, 'admin')
            ->from('/admin/trainers/pending')
            ->post("/admin/trainers/{$formateur->id}/reject", [])
            ->assertRedirect('/admin/trainers/pending')
            ->assertSessionHasErrors('reason');

        $this->actingAs($admin, 'admin')
            ->post("/admin/trainers/{$formateur->id}/reject", ['reason' => 'Profil incomplet'])
            ->assertRedirect();

        $this->assertDatabaseHas('formateurs', [
            'id' => $formateur->id,
            'statut_validation' => StatutCompte::REJETE->value,
        ]);
        $this->assertDatabaseHas('utilisateurs', [
            'id' => $user->id,
            'statut' => StatutCompte::REJETE->value,
        ]);
    }

    public function test_admin_can_validate_trainer_justificatif(): void
    {
        $admin = $this->admin();
        [, $formateur] = $this->pendingTrainer();
        $justificatif = \App\Models\JustificatifFormateur::factory()->create([
            'formateur_id' => $formateur->id,
            'statut' => StatutJustificatif::EN_ATTENTE,
        ]);

        $this->actingAs($admin, 'admin')
            ->post("/admin/justificatifs/{$justificatif->id}/validate")
            ->assertRedirect();

        $this->assertDatabaseHas('justificatif_formateurs', [
            'id' => $justificatif->id,
            'administrateur_validateur_id' => $admin->id,
            'statut' => StatutJustificatif::VALIDE->value,
        ]);
    }

    public function test_admin_can_validate_pending_course(): void
    {
        $admin = $this->admin();
        $cours = Cours::factory()->create(['statut' => StatutCours::EN_ATTENTE_VALIDATION]);

        $this->actingAs($admin, 'admin')
            ->post("/admin/courses/{$cours->id}/validate")
            ->assertRedirect();

        $this->assertDatabaseHas('cours', [
            'id' => $cours->id,
            'administrateur_validateur_id' => $admin->id,
            'statut' => StatutCours::PUBLIE->value,
            'motif_rejet' => null,
        ]);
        $this->assertNotNull($cours->fresh()->date_publication);
    }

    public function test_admin_cannot_validate_course_that_is_not_pending_validation(): void
    {
        $admin = $this->admin();
        $cours = Cours::factory()->create(['statut' => StatutCours::BROUILLON]);

        $this->actingAs($admin, 'admin')
            ->post("/admin/courses/{$cours->id}/validate")
            ->assertRedirect()
            ->assertSessionHasErrors('validation');

        $this->assertDatabaseHas('cours', [
            'id' => $cours->id,
            'statut' => StatutCours::BROUILLON->value,
            'administrateur_validateur_id' => null,
        ]);
    }

    public function test_admin_cannot_validate_trainer_that_is_not_pending(): void
    {
        $admin = $this->admin();
        $utilisateur = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);
        $formateur = Formateur::factory()->create([
            'utilisateur_id' => $utilisateur->id,
            'statut_validation' => StatutCompte::ACTIF,
        ]);

        $this->actingAs($admin, 'admin')
            ->post("/admin/trainers/{$formateur->id}/validate")
            ->assertRedirect()
            ->assertSessionHasErrors('validation');

        $this->assertDatabaseHas('formateurs', [
            'id' => $formateur->id,
            'statut_validation' => StatutCompte::ACTIF->value,
            'administrateur_validateur_id' => null,
        ]);
    }

    public function test_admin_cannot_reject_course_that_is_not_pending_validation(): void
    {
        $admin = $this->admin();
        $cours = Cours::factory()->create(['statut' => StatutCours::PUBLIE]);

        $this->actingAs($admin, 'admin')
            ->post("/admin/courses/{$cours->id}/reject", ['motif_rejet' => 'Hors workflow'])
            ->assertRedirect()
            ->assertSessionHasErrors('validation');

        $this->assertDatabaseHas('cours', [
            'id' => $cours->id,
            'statut' => StatutCours::PUBLIE->value,
            'motif_rejet' => null,
        ]);
    }

    public function test_course_rejection_requires_motif_rejet(): void
    {
        $admin = $this->admin();
        $cours = Cours::factory()->create(['statut' => StatutCours::EN_ATTENTE_VALIDATION]);

        $this->actingAs($admin, 'admin')
            ->from('/admin/courses/pending')
            ->post("/admin/courses/{$cours->id}/reject", [])
            ->assertRedirect('/admin/courses/pending')
            ->assertSessionHasErrors('motif_rejet');

        $this->actingAs($admin, 'admin')
            ->post("/admin/courses/{$cours->id}/reject", ['motif_rejet' => 'Objectifs manquants'])
            ->assertRedirect();

        $this->assertDatabaseHas('cours', [
            'id' => $cours->id,
            'statut' => StatutCours::REJETE->value,
            'motif_rejet' => 'Objectifs manquants',
        ]);
    }

    public function test_admin_can_manage_categories_and_subcategories(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->post('/admin/categories', [
                'nom' => 'Developpement',
                'description' => 'Cours techniques',
            ])
            ->assertRedirect('/admin/categories');

        $parent = Categorie::query()->where('nom', 'Developpement')->firstOrFail();

        $this->actingAs($admin, 'admin')
            ->post('/admin/categories', [
                'parent_id' => $parent->id,
                'nom' => 'Laravel',
                'description' => 'Framework PHP',
            ])
            ->assertRedirect('/admin/categories');

        $child = Categorie::query()->where('nom', 'Laravel')->firstOrFail();

        $this->actingAs($admin, 'admin')
            ->put("/admin/categories/{$child->id}", [
                'parent_id' => $parent->id,
                'nom' => 'Laravel avance',
                'description' => 'Framework PHP moderne',
            ])
            ->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('categories', [
            'id' => $child->id,
            'parent_id' => $parent->id,
            'nom' => 'Laravel avance',
        ]);

        $this->actingAs($admin, 'admin')
            ->delete("/admin/categories/{$child->id}")
            ->assertRedirect('/admin/categories');

        $this->assertSoftDeleted('categories', ['id' => $child->id]);
    }

    private function admin(): Administrateur
    {
        return Administrateur::factory()->create(['statut' => StatutCompte::ACTIF]);
    }

    private function pendingTrainer(): array
    {
        $utilisateur = Utilisateur::factory()->create(['statut' => StatutCompte::EN_ATTENTE]);
        $formateur = Formateur::factory()->create([
            'utilisateur_id' => $utilisateur->id,
            'statut_validation' => StatutCompte::EN_ATTENTE,
        ]);

        return [$utilisateur, $formateur];
    }
}
