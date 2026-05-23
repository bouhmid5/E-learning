<?php

namespace Tests\Feature;

use App\Enums\StatutCompte;
use App\Models\Administrateur;
use App\Models\Candidat;
use App\Models\Formateur;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationAndRolesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_candidate_registration_creates_user_profile_and_logs_in(): void
    {
        $response = $this->post('/register/candidate', [
            'nom' => 'Martin',
            'prenom' => 'Lea',
            'email' => 'lea.martin@example.com',
            'telephone' => '0600000000',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'niveau' => 'debutant',
            'objectif_apprentissage' => 'Apprendre Laravel',
        ]);

        $response->assertRedirect('/candidate/dashboard');
        $this->assertAuthenticated('web');
        $this->assertDatabaseHas('utilisateurs', [
            'email' => 'lea.martin@example.com',
            'statut' => StatutCompte::ACTIF->value,
        ]);
        $this->assertDatabaseHas('candidats', [
            'niveau' => 'debutant',
            'objectif_apprentissage' => 'Apprendre Laravel',
        ]);
    }

    public function test_trainer_registration_creates_pending_trainer_profile_and_logs_in(): void
    {
        $response = $this->post('/register/trainer', [
            'nom' => 'Durand',
            'prenom' => 'Nadia',
            'email' => 'nadia.durand@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'specialite' => 'Laravel',
            'biographie' => 'Formatrice backend.',
        ]);

        $response->assertRedirect('/trainer/dashboard');
        $this->assertAuthenticated('web');
        $this->assertDatabaseHas('formateurs', [
            'specialite' => 'Laravel',
            'statut_validation' => StatutCompte::EN_ATTENTE->value,
        ]);
    }

    public function test_active_candidate_can_login_successfully(): void
    {
        $utilisateur = Utilisateur::factory()->create([
            'email' => 'candidate@example.com',
            'mot_de_passe_hash' => Hash::make('password123'),
            'statut' => StatutCompte::ACTIF,
        ]);
        Candidat::factory()->create(['utilisateur_id' => $utilisateur->id]);

        $response = $this->post('/login', [
            'account_type' => 'utilisateur',
            'email' => 'candidate@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/candidate/dashboard');
        $this->assertAuthenticatedAs($utilisateur, 'web');
    }

    public function test_active_admin_can_login_successfully(): void
    {
        $administrateur = Administrateur::factory()->create([
            'email' => 'admin@example.com',
            'mot_de_passe_hash' => Hash::make('password123'),
            'statut' => StatutCompte::ACTIF,
        ]);

        $response = $this->post('/login', [
            'account_type' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($administrateur, 'admin');
    }

    public function test_rejected_account_cannot_login(): void
    {
        Utilisateur::factory()->create([
            'email' => 'rejected@example.com',
            'mot_de_passe_hash' => Hash::make('password123'),
            'statut' => StatutCompte::REJETE,
        ]);

        $response = $this->post('/login', [
            'email' => 'rejected@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('web');
    }

    public function test_disabled_account_cannot_login(): void
    {
        Utilisateur::factory()->create([
            'email' => 'disabled@example.com',
            'mot_de_passe_hash' => Hash::make('password123'),
            'statut' => StatutCompte::DESACTIVE,
        ]);

        $response = $this->post('/login', [
            'email' => 'disabled@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('web');
    }

    public function test_pending_account_cannot_login(): void
    {
        Utilisateur::factory()->create([
            'email' => 'pending@example.com',
            'mot_de_passe_hash' => Hash::make('password123'),
            'statut' => StatutCompte::EN_ATTENTE,
        ]);

        $response = $this->post('/login', [
            'email' => 'pending@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('web');
    }

    public function test_protected_route_requires_authentication(): void
    {
        $response = $this->get('/candidate/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_role_based_route_protection(): void
    {
        $candidateUser = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);
        Candidat::factory()->create(['utilisateur_id' => $candidateUser->id]);

        $this->actingAs($candidateUser, 'web')
            ->get('/candidate/dashboard')
            ->assertOk();

        $this->actingAs($candidateUser, 'web')
            ->get('/trainer/dashboard')
            ->assertForbidden();

        $trainerUser = Utilisateur::factory()->create(['statut' => StatutCompte::ACTIF]);
        Formateur::factory()->create(['utilisateur_id' => $trainerUser->id]);

        $this->actingAs($trainerUser, 'web')
            ->get('/trainer/dashboard')
            ->assertOk();

        $this->actingAs($trainerUser, 'web')
            ->get('/candidate/dashboard')
            ->assertForbidden();

        $administrateur = Administrateur::factory()->create(['statut' => StatutCompte::ACTIF]);

        $this->actingAs($administrateur, 'admin')
            ->get('/admin/dashboard')
            ->assertOk();
    }
}
