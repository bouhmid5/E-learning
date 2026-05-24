<?php

namespace Tests\Feature;

use App\Models\Candidat;
use App\Models\Certificat;
use App\Models\Cours;
use App\Models\Inscription;
use App\Models\Lecon;
use App\Models\ProgressionLecon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainUniqueConstraintsTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidate_can_enroll_in_a_course_only_once(): void
    {
        $candidat = Candidat::factory()->create();
        $cours = Cours::factory()->create();

        Inscription::factory()->create([
            'candidat_id' => $candidat->id,
            'cours_id' => $cours->id,
        ]);

        $this->expectException(QueryException::class);

        Inscription::factory()->create([
            'candidat_id' => $candidat->id,
            'cours_id' => $cours->id,
        ]);
    }

    public function test_certificate_verification_code_must_be_unique(): void
    {
        $code = 'CERT-UNIQUE-001';

        Certificat::factory()->create(['code_verification' => $code]);

        $this->expectException(QueryException::class);

        Certificat::factory()->create(['code_verification' => $code]);
    }

    public function test_lesson_progression_is_unique_per_inscription_and_lesson(): void
    {
        $cours = Cours::factory()->create();
        $lecon = Lecon::factory()->create(['cours_id' => $cours->id]);
        $inscription = Inscription::factory()->create(['cours_id' => $cours->id]);

        ProgressionLecon::factory()->create([
            'inscription_id' => $inscription->id,
            'lecon_id' => $lecon->id,
        ]);

        $this->expectException(QueryException::class);

        ProgressionLecon::factory()->create([
            'inscription_id' => $inscription->id,
            'lecon_id' => $lecon->id,
        ]);
    }
}

