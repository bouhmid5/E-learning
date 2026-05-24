<?php

namespace Tests\Feature;

use App\Models\Administrateur;
use App\Models\Candidat;
use App\Models\Categorie;
use App\Models\Certificat;
use App\Models\Cours;
use App\Models\CritereCorrection;
use App\Models\Evaluation;
use App\Models\Formateur;
use App\Models\Inscription;
use App\Models\JustificatifFormateur;
use App\Models\Lecon;
use App\Models\OptionReponse;
use App\Models\ProgressionLecon;
use App\Models\Question;
use App\Models\ReponseCandidat;
use App\Models\Ressource;
use App\Models\SoumissionEvaluation;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainModelCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_domain_models_can_be_created(): void
    {
        $administrateur = Administrateur::factory()->create();
        $utilisateur = Utilisateur::factory()->create(['administrateur_id' => $administrateur->id]);
        $candidat = Candidat::factory()->create(['utilisateur_id' => $utilisateur->id]);
        $formateur = Formateur::factory()->create(['administrateur_validateur_id' => $administrateur->id]);
        $justificatif = JustificatifFormateur::factory()->create(['formateur_id' => $formateur->id]);
        $categorie = Categorie::factory()->create();
        $cours = Cours::factory()->create([
            'categorie_id' => $categorie->id,
            'formateur_id' => $formateur->id,
            'administrateur_validateur_id' => $administrateur->id,
        ]);
        $lecon = Lecon::factory()->create(['cours_id' => $cours->id]);
        $ressource = Ressource::factory()->create(['lecon_id' => $lecon->id]);
        $inscription = Inscription::factory()->create([
            'candidat_id' => $candidat->id,
            'cours_id' => $cours->id,
        ]);
        $progression = ProgressionLecon::factory()->create([
            'inscription_id' => $inscription->id,
            'lecon_id' => $lecon->id,
        ]);
        $evaluation = Evaluation::factory()->create(['cours_id' => $cours->id]);
        $question = Question::factory()->create(['evaluation_id' => $evaluation->id]);
        $option = OptionReponse::factory()->create(['question_id' => $question->id]);
        $critere = CritereCorrection::factory()->create(['evaluation_id' => $evaluation->id]);
        $soumission = SoumissionEvaluation::factory()->create([
            'candidat_id' => $candidat->id,
            'evaluation_id' => $evaluation->id,
        ]);
        $reponse = ReponseCandidat::factory()->create([
            'soumission_evaluation_id' => $soumission->id,
            'question_id' => $question->id,
        ]);
        $certificat = Certificat::factory()->create(['inscription_id' => $inscription->id]);

        $this->assertDatabaseHas('utilisateurs', ['id' => $utilisateur->id]);
        $this->assertDatabaseHas('justificatif_formateurs', ['id' => $justificatif->id]);
        $this->assertDatabaseHas('ressources', ['id' => $ressource->id]);
        $this->assertDatabaseHas('progression_lecons', ['id' => $progression->id]);
        $this->assertDatabaseHas('option_reponses', ['id' => $option->id]);
        $this->assertDatabaseHas('critere_corrections', ['id' => $critere->id]);
        $this->assertDatabaseHas('reponse_candidats', ['id' => $reponse->id]);
        $this->assertDatabaseHas('certificats', ['id' => $certificat->id]);
    }
}

