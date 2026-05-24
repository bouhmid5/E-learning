<?php

namespace Tests\Feature;

use App\Enums\StatutCompte;
use App\Enums\StatutInscription;
use App\Models\Candidat;
use App\Models\Certificat;
use App\Models\Cours;
use App\Models\Evaluation;
use App\Models\Inscription;
use App\Models\SoumissionEvaluation;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CertificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Storage::fake('public');
    }

    public function test_not_eligible_before_completion(): void
    {
        [$user, $inscription] = $this->candidateWithInscription([
            'progression' => 80,
            'certificat_eligible' => false,
        ]);

        $this->actingAs($user, 'web')
            ->get("/candidate/enrollments/{$inscription->id}/certificate/eligibility")
            ->assertOk()
            ->assertSee('Progression complete')
            ->assertSee('Non');

        $this->actingAs($user, 'web')
            ->from("/candidate/enrollments/{$inscription->id}/certificate/eligibility")
            ->post("/candidate/enrollments/{$inscription->id}/certificate")
            ->assertSessionHasErrors('certificate');

        $this->assertDatabaseCount('certificats', 0);
    }

    public function test_not_eligible_before_passing_evaluations(): void
    {
        [$user, $inscription] = $this->eligibleBaseInscription();
        Evaluation::factory()->create([
            'cours_id' => $inscription->cours_id,
            'actif' => true,
        ]);

        $this->actingAs($user, 'web')
            ->get("/candidate/enrollments/{$inscription->id}/certificate/eligibility")
            ->assertOk()
            ->assertSee('Evaluations reussies')
            ->assertSee('Non');

        $this->actingAs($user, 'web')
            ->post("/candidate/enrollments/{$inscription->id}/certificate")
            ->assertSessionHasErrors('certificate');

        $this->assertDatabaseCount('certificats', 0);
    }

    public function test_certificate_generated_once(): void
    {
        [$user, $inscription] = $this->fullyEligibleInscription();

        $this->actingAs($user, 'web')
            ->post("/candidate/enrollments/{$inscription->id}/certificate")
            ->assertRedirect('/candidate/certificates');

        $certificat = Certificat::query()->firstOrFail();

        $this->assertDatabaseHas('certificats', [
            'inscription_id' => $inscription->id,
            'code_verification' => $certificat->code_verification,
            'actif' => true,
        ]);
        Storage::disk('public')->assertExists($certificat->fichier_url);
    }

    public function test_duplicate_certificate_generation_is_idempotent(): void
    {
        [$user, $inscription] = $this->fullyEligibleInscription();

        $this->actingAs($user, 'web')->post("/candidate/enrollments/{$inscription->id}/certificate");
        $first = Certificat::query()->firstOrFail();

        $this->actingAs($user, 'web')->post("/candidate/enrollments/{$inscription->id}/certificate");
        $second = Certificat::query()->firstOrFail();

        $this->assertSame($first->id, $second->id);
        $this->assertDatabaseCount('certificats', 1);
    }

    public function test_verification_by_code_works(): void
    {
        [, $inscription] = $this->fullyEligibleInscription();
        $certificat = Certificat::factory()->create([
            'inscription_id' => $inscription->id,
            'code_verification' => 'CERT-VERIFY-123',
            'actif' => true,
        ]);

        $this->get("/certificates/verify/{$certificat->code_verification}")
            ->assertOk()
            ->assertSee('Certificat valide')
            ->assertSee('CERT-VERIFY-123');
    }

    public function test_only_owner_can_download_certificate(): void
    {
        [$owner, $inscription] = $this->fullyEligibleInscription();
        [$otherUser] = $this->candidateWithInscription();

        $certificat = Certificat::factory()->create([
            'inscription_id' => $inscription->id,
            'code_verification' => 'CERT-DOWNLOAD-1',
            'fichier_url' => 'certificates/CERT-DOWNLOAD-1.txt',
            'actif' => true,
        ]);
        Storage::disk('public')->put($certificat->fichier_url, 'certificate');

        $this->actingAs($owner, 'web')
            ->get("/candidate/certificates/{$certificat->id}/download")
            ->assertOk();

        $this->actingAs($otherUser, 'web')
            ->get("/candidate/certificates/{$certificat->id}/download")
            ->assertForbidden();
    }

    private function candidateWithInscription(array $overrides = []): array
    {
        $user = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);
        $candidat = Candidat::factory()->create(['utilisateur_id' => $user->id]);
        $cours = Cours::factory()->create();
        $inscription = Inscription::factory()->create(array_merge([
            'candidat_id' => $candidat->id,
            'cours_id' => $cours->id,
            'progression' => 0,
            'statut' => StatutInscription::EN_COURS,
            'certificat_eligible' => false,
        ], $overrides));

        return [$user, $inscription];
    }

    private function eligibleBaseInscription(): array
    {
        return $this->candidateWithInscription([
            'progression' => 100,
            'statut' => StatutInscription::TERMINEE,
            'date_fin' => now(),
            'certificat_eligible' => true,
        ]);
    }

    private function fullyEligibleInscription(): array
    {
        [$user, $inscription] = $this->eligibleBaseInscription();
        $evaluation = Evaluation::factory()->create([
            'cours_id' => $inscription->cours_id,
            'actif' => true,
        ]);

        SoumissionEvaluation::factory()->create([
            'candidat_id' => $user->candidat->id,
            'evaluation_id' => $evaluation->id,
            'date_soumission' => now(),
            'score_obtenu' => 100,
            'reussi' => true,
        ]);

        return [$user, $inscription];
    }
}
