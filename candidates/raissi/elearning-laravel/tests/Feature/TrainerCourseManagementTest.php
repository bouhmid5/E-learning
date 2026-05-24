<?php

namespace Tests\Feature;

use App\Enums\StatutCours;
use App\Enums\StatutCompte;
use App\Enums\TypeRessource;
use App\Models\Categorie;
use App\Models\Cours;
use App\Models\Formateur;
use App\Models\Lecon;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TrainerCourseManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_trainer_can_create_own_course_as_draft(): void
    {
        [$utilisateur, $formateur] = $this->trainer();
        $categorie = Categorie::factory()->create();

        $response = $this->actingAs($utilisateur, 'web')->post('/trainer/courses', [
            'categorie_id' => $categorie->id,
            'titre' => 'Nouveau cours',
            'description' => 'Description',
            'niveau' => 'debutant',
            'langue' => 'fr',
            'prix' => 25,
            'duree_estimee' => 90,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cours', [
            'titre' => 'Nouveau cours',
            'formateur_id' => $formateur->id,
            'statut' => StatutCours::BROUILLON->value,
        ]);
    }

    public function test_trainer_cannot_edit_another_trainers_course(): void
    {
        [$utilisateur] = $this->trainer();
        [, $otherFormateur] = $this->trainer();
        $cours = Cours::factory()->create([
            'formateur_id' => $otherFormateur->id,
            'statut' => StatutCours::BROUILLON,
        ]);

        $this->actingAs($utilisateur, 'web')->put("/trainer/courses/{$cours->id}", [
            'categorie_id' => $cours->categorie_id,
            'titre' => 'Tentative',
            'description' => 'Non',
            'niveau' => 'debutant',
            'langue' => 'fr',
            'prix' => 10,
            'duree_estimee' => 60,
        ])->assertForbidden();
    }

    public function test_course_without_lesson_cannot_be_submitted(): void
    {
        [$utilisateur, $formateur] = $this->trainer();
        $cours = Cours::factory()->create([
            'formateur_id' => $formateur->id,
            'statut' => StatutCours::BROUILLON,
        ]);

        $this->actingAs($utilisateur, 'web')
            ->from("/trainer/courses/{$cours->id}")
            ->post("/trainer/courses/{$cours->id}/submit")
            ->assertRedirect("/trainer/courses/{$cours->id}")
            ->assertSessionHasErrors('cours');

        $this->assertDatabaseHas('cours', [
            'id' => $cours->id,
            'statut' => StatutCours::BROUILLON->value,
        ]);
    }

    public function test_submitted_course_becomes_pending_validation(): void
    {
        [$utilisateur, $formateur] = $this->trainer();
        $cours = Cours::factory()->create([
            'formateur_id' => $formateur->id,
            'statut' => StatutCours::BROUILLON,
        ]);
        Lecon::factory()->create(['cours_id' => $cours->id]);

        $this->actingAs($utilisateur, 'web')
            ->post("/trainer/courses/{$cours->id}/submit")
            ->assertRedirect("/trainer/courses/{$cours->id}");

        $this->assertDatabaseHas('cours', [
            'id' => $cours->id,
            'statut' => StatutCours::EN_ATTENTE_VALIDATION->value,
        ]);
    }

    public function test_rejected_course_can_be_edited(): void
    {
        [$utilisateur, $formateur] = $this->trainer();
        $categorie = Categorie::factory()->create();
        $cours = Cours::factory()->create([
            'formateur_id' => $formateur->id,
            'statut' => StatutCours::REJETE,
            'motif_rejet' => 'Contenu incomplet',
        ]);

        $this->actingAs($utilisateur, 'web')->put("/trainer/courses/{$cours->id}", [
            'categorie_id' => $categorie->id,
            'titre' => 'Cours corrige',
            'description' => 'Corrige',
            'niveau' => 'intermediaire',
            'langue' => 'fr',
            'prix' => 80,
            'duree_estimee' => 120,
        ])->assertRedirect("/trainer/courses/{$cours->id}");

        $this->assertDatabaseHas('cours', [
            'id' => $cours->id,
            'titre' => 'Cours corrige',
            'statut' => StatutCours::REJETE->value,
        ]);
    }

    public function test_published_course_cannot_be_edited_directly_by_trainer(): void
    {
        [$utilisateur, $formateur] = $this->trainer();
        $cours = Cours::factory()->create([
            'formateur_id' => $formateur->id,
            'statut' => StatutCours::PUBLIE,
        ]);

        $this->actingAs($utilisateur, 'web')->put("/trainer/courses/{$cours->id}", [
            'categorie_id' => $cours->categorie_id,
            'titre' => 'Modification interdite',
            'description' => 'Non',
            'niveau' => 'avance',
            'langue' => 'fr',
            'prix' => 200,
            'duree_estimee' => 240,
        ])->assertForbidden();
    }

    public function test_resource_upload_rejects_unsupported_file_type(): void
    {
        [$utilisateur, $formateur] = $this->trainer();
        $cours = Cours::factory()->create([
            'formateur_id' => $formateur->id,
            'statut' => StatutCours::BROUILLON,
        ]);
        $lecon = Lecon::factory()->create(['cours_id' => $cours->id]);

        $this->actingAs($utilisateur, 'web')
            ->from("/trainer/courses/{$cours->id}")
            ->post("/trainer/lessons/{$lecon->id}/resources", [
                'titre' => 'Script dangereux',
                'type' => TypeRessource::DOCUMENT->value,
                'fichier' => UploadedFile::fake()->create('script.exe', 1, 'application/x-msdownload'),
                'ordre' => 1,
            ])
            ->assertRedirect("/trainer/courses/{$cours->id}")
            ->assertSessionHasErrors('fichier');
    }

    public function test_resource_link_requires_http_or_https_url(): void
    {
        [$utilisateur, $formateur] = $this->trainer();
        $cours = Cours::factory()->create([
            'formateur_id' => $formateur->id,
            'statut' => StatutCours::BROUILLON,
        ]);
        $lecon = Lecon::factory()->create(['cours_id' => $cours->id]);

        $this->actingAs($utilisateur, 'web')
            ->from("/trainer/courses/{$cours->id}")
            ->post("/trainer/lessons/{$lecon->id}/resources", [
                'titre' => 'Lien non sur',
                'type' => TypeRessource::LIEN->value,
                'url' => 'javascript:alert(1)',
                'ordre' => 1,
            ])
            ->assertRedirect("/trainer/courses/{$cours->id}")
            ->assertSessionHasErrors('url');
    }

    private function trainer(): array
    {
        $utilisateur = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);
        $formateur = Formateur::factory()->create([
            'utilisateur_id' => $utilisateur->id,
            'statut_validation' => StatutCompte::ACTIF,
        ]);

        return [$utilisateur, $formateur];
    }
}
