<?php

namespace Tests\Feature;

use App\Enums\StatutCompte;
use App\Enums\StatutCours;
use App\Enums\StatutInscription;
use App\Models\Candidat;
use App\Models\Cours;
use App\Models\Inscription;
use App\Models\Lecon;
use App\Models\Ressource;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidateEnrollmentProgressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_enrollment_requires_candidate_auth(): void
    {
        $cours = Cours::factory()->create(['statut' => StatutCours::PUBLIE]);

        $this->post("/courses/{$cours->id}/enroll")
            ->assertRedirect('/login');
    }

    public function test_candidate_can_enroll_only_in_published_courses(): void
    {
        [$utilisateur] = $this->candidate();
        $draft = Cours::factory()->create(['statut' => StatutCours::BROUILLON]);

        $this->actingAs($utilisateur, 'web')
            ->from("/courses/{$draft->id}")
            ->post("/courses/{$draft->id}/enroll")
            ->assertRedirect("/courses/{$draft->id}")
            ->assertSessionHasErrors('enrollment');

        $this->assertDatabaseCount('inscriptions', 0);

        $published = Cours::factory()->create(['statut' => StatutCours::PUBLIE]);

        $this->actingAs($utilisateur, 'web')
            ->post("/courses/{$published->id}/enroll")
            ->assertRedirect();

        $this->assertDatabaseHas('inscriptions', [
            'cours_id' => $published->id,
            'statut' => StatutInscription::EN_COURS->value,
        ]);
    }

    public function test_duplicate_enrollment_is_blocked(): void
    {
        [$utilisateur, $candidat] = $this->candidate();
        $cours = Cours::factory()->create(['statut' => StatutCours::PUBLIE]);
        Inscription::factory()->create([
            'candidat_id' => $candidat->id,
            'cours_id' => $cours->id,
        ]);

        $this->actingAs($utilisateur, 'web')
            ->from("/courses/{$cours->id}")
            ->post("/courses/{$cours->id}/enroll")
            ->assertRedirect("/courses/{$cours->id}")
            ->assertSessionHasErrors('enrollment');

        $this->assertDatabaseCount('inscriptions', 1);
    }

    public function test_lesson_access_blocked_without_enrollment(): void
    {
        [$utilisateur] = $this->candidate();
        [, $otherCandidate] = $this->candidate();
        $inscription = Inscription::factory()->create(['candidat_id' => $otherCandidate->id]);

        $this->actingAs($utilisateur, 'web')
            ->get("/candidate/enrollments/{$inscription->id}/lessons")
            ->assertForbidden();
    }

    public function test_marking_lesson_complete_creates_or_updates_progression(): void
    {
        [$utilisateur, $candidat] = $this->candidate();
        $cours = Cours::factory()->create(['statut' => StatutCours::PUBLIE]);
        $lessonOne = Lecon::factory()->create(['cours_id' => $cours->id]);
        Lecon::factory()->create(['cours_id' => $cours->id]);
        $inscription = Inscription::factory()->create([
            'candidat_id' => $candidat->id,
            'cours_id' => $cours->id,
        ]);

        $this->actingAs($utilisateur, 'web')
            ->post("/candidate/enrollments/{$inscription->id}/lessons/{$lessonOne->id}/complete")
            ->assertRedirect();

        $this->assertDatabaseHas('progression_lecons', [
            'inscription_id' => $inscription->id,
            'lecon_id' => $lessonOne->id,
            'terminee' => true,
        ]);

        $this->assertSame('50.00', $inscription->fresh()->progression);
    }

    public function test_progression_calculation_works(): void
    {
        [$utilisateur, $candidat] = $this->candidate();
        $cours = Cours::factory()->create(['statut' => StatutCours::PUBLIE]);
        $lessonOne = Lecon::factory()->create(['cours_id' => $cours->id]);
        Lecon::factory()->create(['cours_id' => $cours->id]);
        $inscription = Inscription::factory()->create([
            'candidat_id' => $candidat->id,
            'cours_id' => $cours->id,
        ]);

        \App\Models\ProgressionLecon::factory()->create([
            'inscription_id' => $inscription->id,
            'lecon_id' => $lessonOne->id,
            'terminee' => true,
            'date_completion' => now(),
        ]);

        $this->actingAs($utilisateur, 'web')
            ->getJson("/candidate/enrollments/{$inscription->id}/progress")
            ->assertOk()
            ->assertJson([
                'progression' => 50,
                'statut' => StatutInscription::EN_COURS->value,
            ]);
    }

    public function test_full_progression_marks_inscription_finished(): void
    {
        [$utilisateur, $candidat] = $this->candidate();
        $cours = Cours::factory()->create(['statut' => StatutCours::PUBLIE]);
        $lessonOne = Lecon::factory()->create(['cours_id' => $cours->id]);
        $lessonTwo = Lecon::factory()->create(['cours_id' => $cours->id]);
        $inscription = Inscription::factory()->create([
            'candidat_id' => $candidat->id,
            'cours_id' => $cours->id,
        ]);

        $this->actingAs($utilisateur, 'web')
            ->post("/candidate/enrollments/{$inscription->id}/lessons/{$lessonOne->id}/complete")
            ->assertRedirect();

        $this->actingAs($utilisateur, 'web')
            ->post("/candidate/enrollments/{$inscription->id}/lessons/{$lessonTwo->id}/complete")
            ->assertRedirect();

        $this->assertDatabaseHas('inscriptions', [
            'id' => $inscription->id,
            'progression' => 100,
            'statut' => StatutInscription::TERMINEE->value,
            'certificat_eligible' => true,
        ]);
        $this->assertNotNull($inscription->fresh()->date_fin);
    }

    public function test_resource_download_requires_matching_enrollment(): void
    {
        [$utilisateur, $candidat] = $this->candidate();
        $cours = Cours::factory()->create(['statut' => StatutCours::PUBLIE]);
        $lesson = Lecon::factory()->create(['cours_id' => $cours->id]);
        $ressource = Ressource::factory()->create([
            'lecon_id' => $lesson->id,
            'url' => 'https://example.test/support.pdf',
            'telechargeable' => true,
        ]);
        $inscription = Inscription::factory()->create([
            'candidat_id' => $candidat->id,
            'cours_id' => $cours->id,
        ]);

        $this->actingAs($utilisateur, 'web')
            ->get("/candidate/enrollments/{$inscription->id}/resources/{$ressource->id}/download")
            ->assertRedirect('https://example.test/support.pdf');

        [, $otherCandidate] = $this->candidate();
        $otherInscription = Inscription::factory()->create(['candidat_id' => $otherCandidate->id]);

        $this->actingAs($utilisateur, 'web')
            ->get("/candidate/enrollments/{$otherInscription->id}/resources/{$ressource->id}/download")
            ->assertForbidden();
    }

    private function candidate(): array
    {
        $utilisateur = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);
        $candidat = Candidat::factory()->create(['utilisateur_id' => $utilisateur->id]);

        return [$utilisateur, $candidat];
    }
}
