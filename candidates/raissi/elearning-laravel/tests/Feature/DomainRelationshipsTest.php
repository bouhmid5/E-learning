<?php

namespace Tests\Feature;

use App\Models\Candidat;
use App\Models\Categorie;
use App\Models\Certificat;
use App\Models\Cours;
use App\Models\Evaluation;
use App\Models\Formateur;
use App\Models\Inscription;
use App\Models\Lecon;
use App\Models\OptionReponse;
use App\Models\ProgressionLecon;
use App\Models\Question;
use App\Models\ReponseCandidat;
use App\Models\Ressource;
use App\Models\SoumissionEvaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_key_relationships_are_available(): void
    {
        $parent = Categorie::factory()->create();
        $child = Categorie::factory()->create(['parent_id' => $parent->id]);
        $formateur = Formateur::factory()->create();
        $candidat = Candidat::factory()->create();
        $cours = Cours::factory()->create([
            'categorie_id' => $child->id,
            'formateur_id' => $formateur->id,
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
        $soumission = SoumissionEvaluation::factory()->create([
            'candidat_id' => $candidat->id,
            'evaluation_id' => $evaluation->id,
        ]);
        $reponse = ReponseCandidat::factory()->create([
            'soumission_evaluation_id' => $soumission->id,
            'question_id' => $question->id,
        ]);
        $certificat = Certificat::factory()->create(['inscription_id' => $inscription->id]);

        $this->assertTrue($child->parent->is($parent));
        $this->assertTrue($parent->enfants->contains($child));
        $this->assertTrue($cours->categorie->is($child));
        $this->assertTrue($cours->formateur->is($formateur));
        $this->assertTrue($cours->lecons->contains($lecon));
        $this->assertTrue($lecon->ressources->contains($ressource));
        $this->assertTrue($candidat->inscriptions->contains($inscription));
        $this->assertTrue($inscription->progressionsLecons->contains($progression));
        $this->assertTrue($evaluation->questions->contains($question));
        $this->assertTrue($question->optionsReponse->contains($option));
        $this->assertTrue($soumission->reponsesCandidats->contains($reponse));
        $this->assertTrue($inscription->certificat->is($certificat));
    }
}

