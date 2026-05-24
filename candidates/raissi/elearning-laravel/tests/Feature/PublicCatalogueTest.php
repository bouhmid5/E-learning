<?php

namespace Tests\Feature;

use App\Enums\StatutCours;
use App\Models\Categorie;
use App\Models\Cours;
use App\Models\Formateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCatalogueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_only_published_courses_are_visible(): void
    {
        Cours::factory()->create(['titre' => 'Cours publié', 'statut' => StatutCours::PUBLIE]);
        Cours::factory()->create(['titre' => 'Cours brouillon', 'statut' => StatutCours::BROUILLON]);
        Cours::factory()->create(['titre' => 'Cours rejeté', 'statut' => StatutCours::REJETE]);
        Cours::factory()->create(['titre' => 'Cours archivé', 'statut' => StatutCours::ARCHIVE]);

        $response = $this->get('/courses');

        $response->assertOk();
        $response->assertSee('Cours publié');
        $response->assertDontSee('Cours brouillon');
        $response->assertDontSee('Cours rejeté');
        $response->assertDontSee('Cours archivé');
    }

    public function test_search_courses_by_title_and_description(): void
    {
        Cours::factory()->create([
            'titre' => 'Laravel catalogue',
            'description' => 'Framework PHP',
            'statut' => StatutCours::PUBLIE,
        ]);
        Cours::factory()->create([
            'titre' => 'SQL avancé',
            'description' => 'Optimisation de requêtes',
            'statut' => StatutCours::PUBLIE,
        ]);

        $this->get('/courses?search=Laravel')
            ->assertOk()
            ->assertSee('Laravel catalogue')
            ->assertDontSee('SQL avancé');

        $this->get('/courses?search=Optimisation')
            ->assertOk()
            ->assertSee('SQL avancé')
            ->assertDontSee('Laravel catalogue');
    }

    public function test_simple_and_advanced_filters_work(): void
    {
        $web = Categorie::factory()->create(['nom' => 'Web']);
        $data = Categorie::factory()->create(['nom' => 'Data']);
        $trainer = Formateur::factory()->create();
        $otherTrainer = Formateur::factory()->create();

        Cours::factory()->create([
            'titre' => 'Laravel complet',
            'description' => 'Backend moderne',
            'categorie_id' => $web->id,
            'formateur_id' => $trainer->id,
            'niveau' => 'debutant',
            'langue' => 'fr',
            'prix' => 99,
            'duree_estimee' => 120,
            'statut' => StatutCours::PUBLIE,
        ]);
        Cours::factory()->create([
            'titre' => 'Analyse SQL',
            'description' => 'Data warehouse',
            'categorie_id' => $data->id,
            'formateur_id' => $otherTrainer->id,
            'niveau' => 'avance',
            'langue' => 'en',
            'prix' => 250,
            'duree_estimee' => 400,
            'statut' => StatutCours::PUBLIE,
        ]);

        $query = http_build_query([
            'category' => $web->id,
            'niveau' => 'debutant',
            'langue' => 'fr',
            'min_price' => 50,
            'max_price' => 150,
            'min_duration' => 60,
            'max_duration' => 200,
            'trainer' => $trainer->id,
            'keyword' => 'Backend',
        ]);

        $this->get("/courses?{$query}")
            ->assertOk()
            ->assertSee('Laravel complet')
            ->assertDontSee('Analyse SQL');
    }

    public function test_sorting_works(): void
    {
        Cours::factory()->create([
            'titre' => 'Cours cher',
            'prix' => 300,
            'statut' => StatutCours::PUBLIE,
        ]);
        Cours::factory()->create([
            'titre' => 'Cours accessible',
            'prix' => 10,
            'statut' => StatutCours::PUBLIE,
        ]);

        $this->get('/courses?sort=prix&direction=asc')
            ->assertOk()
            ->assertSeeInOrder(['Cours accessible', 'Cours cher']);
    }

    public function test_unpublished_course_details_are_not_accessible_publicly(): void
    {
        $cours = Cours::factory()->create(['statut' => StatutCours::BROUILLON]);

        $this->get("/courses/{$cours->id}")
            ->assertNotFound();
    }
}

