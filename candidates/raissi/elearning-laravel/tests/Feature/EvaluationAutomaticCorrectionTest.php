<?php

namespace Tests\Feature;

use App\Enums\StatutCompte;
use App\Enums\StatutCours;
use App\Enums\TypeQuestion;
use App\Models\Candidat;
use App\Models\Cours;
use App\Models\CritereCorrection;
use App\Models\Evaluation;
use App\Models\Formateur;
use App\Models\Inscription;
use App\Models\OptionReponse;
use App\Models\Question;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluationAutomaticCorrectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_trainer_can_only_manage_evaluations_for_own_course(): void
    {
        [$ownerUser, $ownerTrainer] = $this->trainer();
        [$otherUser] = $this->trainer();
        $cours = Cours::factory()->create([
            'formateur_id' => $ownerTrainer->id,
            'statut' => StatutCours::BROUILLON,
        ]);
        $evaluation = Evaluation::factory()->create(['cours_id' => $cours->id]);

        $this->actingAs($ownerUser, 'web')
            ->get("/trainer/evaluations/{$evaluation->id}/edit")
            ->assertOk();

        $this->actingAs($otherUser, 'web')
            ->get("/trainer/evaluations/{$evaluation->id}/edit")
            ->assertForbidden();
    }

    public function test_candidate_must_be_enrolled_to_submit(): void
    {
        [$candidateUser] = $this->candidate();
        $evaluation = Evaluation::factory()->create();

        $this->actingAs($candidateUser, 'web')
            ->post("/candidate/evaluations/{$evaluation->id}/submit", [
                'answers' => [],
            ])
            ->assertForbidden();
    }

    public function test_qcm_scoring(): void
    {
        [$candidateUser] = $this->candidate();
        $evaluation = $this->evaluationForEnrolledCandidate($candidateUser);
        $question = Question::factory()->create([
            'evaluation_id' => $evaluation->id,
            'type' => TypeQuestion::QCM,
            'points' => 4,
        ]);
        $correct = OptionReponse::factory()->create(['question_id' => $question->id, 'est_correcte' => true]);
        OptionReponse::factory()->create(['question_id' => $question->id, 'est_correcte' => false]);

        $this->actingAs($candidateUser, 'web')
            ->post("/candidate/evaluations/{$evaluation->id}/submit", [
                'answers' => [
                    $question->id => [$correct->id],
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('soumission_evaluations', [
            'evaluation_id' => $evaluation->id,
            'score_obtenu' => 4,
            'reussi' => true,
        ]);
        $this->assertDatabaseHas('reponse_candidats', [
            'question_id' => $question->id,
            'est_correcte' => true,
            'points_obtenus' => 4,
        ]);
    }

    public function test_vrai_faux_scoring(): void
    {
        [$candidateUser] = $this->candidate();
        $evaluation = $this->evaluationForEnrolledCandidate($candidateUser);
        $question = Question::factory()->create([
            'evaluation_id' => $evaluation->id,
            'type' => TypeQuestion::VRAI_FAUX,
            'points' => 2,
        ]);
        $trueOption = OptionReponse::factory()->create(['question_id' => $question->id, 'texte' => 'Vrai', 'est_correcte' => true]);
        OptionReponse::factory()->create(['question_id' => $question->id, 'texte' => 'Faux', 'est_correcte' => false]);

        $this->actingAs($candidateUser, 'web')
            ->post("/candidate/evaluations/{$evaluation->id}/submit", [
                'answers' => [
                    $question->id => $trueOption->id,
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('soumission_evaluations', [
            'evaluation_id' => $evaluation->id,
            'score_obtenu' => 2,
            'reussi' => true,
        ]);
    }

    public function test_numeric_tolerance_scoring(): void
    {
        [$candidateUser] = $this->candidate();
        $evaluation = $this->evaluationForEnrolledCandidate($candidateUser);
        $question = Question::factory()->create([
            'evaluation_id' => $evaluation->id,
            'type' => TypeQuestion::NUMERIQUE,
            'points' => 3,
        ]);
        CritereCorrection::factory()->create([
            'evaluation_id' => $evaluation->id,
            'question_id' => $question->id,
            'valeur_attendue' => '10',
            'tolerance' => 0.5,
        ]);

        $this->actingAs($candidateUser, 'web')
            ->post("/candidate/evaluations/{$evaluation->id}/submit", [
                'answers' => [
                    $question->id => '10.4',
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('soumission_evaluations', [
            'evaluation_id' => $evaluation->id,
            'score_obtenu' => 3,
            'reussi' => true,
        ]);
    }

    public function test_short_answer_normalized_scoring(): void
    {
        [$candidateUser] = $this->candidate();
        $evaluation = $this->evaluationForEnrolledCandidate($candidateUser);
        $question = Question::factory()->create([
            'evaluation_id' => $evaluation->id,
            'type' => TypeQuestion::REPONSE_COURTE,
            'points' => 5,
        ]);
        CritereCorrection::factory()->create([
            'evaluation_id' => $evaluation->id,
            'question_id' => $question->id,
            'valeur_attendue' => 'Réponse courte',
        ]);

        $this->actingAs($candidateUser, 'web')
            ->post("/candidate/evaluations/{$evaluation->id}/submit", [
                'answers' => [
                    $question->id => '  reponse   courte ',
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('soumission_evaluations', [
            'evaluation_id' => $evaluation->id,
            'score_obtenu' => 5,
            'reussi' => true,
        ]);
    }

    public function test_pass_fail_threshold(): void
    {
        [$candidateUser] = $this->candidate();
        $evaluation = $this->evaluationForEnrolledCandidate($candidateUser, ['seuil_reussite' => 5]);
        $question = Question::factory()->create([
            'evaluation_id' => $evaluation->id,
            'type' => TypeQuestion::REPONSE_COURTE,
            'points' => 5,
        ]);
        CritereCorrection::factory()->create([
            'evaluation_id' => $evaluation->id,
            'question_id' => $question->id,
            'valeur_attendue' => 'correct',
        ]);

        $this->actingAs($candidateUser, 'web')
            ->post("/candidate/evaluations/{$evaluation->id}/submit", [
                'answers' => [
                    $question->id => 'incorrect',
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('soumission_evaluations', [
            'evaluation_id' => $evaluation->id,
            'score_obtenu' => 0,
            'reussi' => false,
        ]);

        $this->actingAs($candidateUser, 'web')
            ->post("/candidate/evaluations/{$evaluation->id}/submit", [
                'answers' => [
                    $question->id => 'correct',
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('soumission_evaluations', [
            'evaluation_id' => $evaluation->id,
            'score_obtenu' => 5,
            'reussi' => true,
        ]);
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

    private function candidate(): array
    {
        $utilisateur = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);
        $candidat = Candidat::factory()->create(['utilisateur_id' => $utilisateur->id]);

        return [$utilisateur, $candidat];
    }

    private function evaluationForEnrolledCandidate(Utilisateur $candidateUser, array $overrides = []): Evaluation
    {
        $cours = Cours::factory()->create(['statut' => StatutCours::PUBLIE]);
        Inscription::factory()->create([
            'candidat_id' => $candidateUser->candidat->id,
            'cours_id' => $cours->id,
        ]);

        return Evaluation::factory()->create(array_merge([
            'cours_id' => $cours->id,
            'score_max' => 10,
            'seuil_reussite' => 1,
            'actif' => true,
        ], $overrides));
    }
}

