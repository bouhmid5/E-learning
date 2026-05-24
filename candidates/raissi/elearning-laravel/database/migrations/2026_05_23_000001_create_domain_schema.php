<?php

use App\Enums\StatutCompte;
use App\Enums\StatutCours;
use App\Enums\StatutInscription;
use App\Enums\StatutJustificatif;
use App\Enums\StatutSoumission;
use App\Enums\TypeEvaluation;
use App\Enums\TypeQuestion;
use App\Enums\TypeRessource;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administrateurs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique('administrateurs_email_unique');
            $table->string('mot_de_passe_hash');
            $table->string('niveau_acces')->default('standard');
            $table->string('statut')->default(StatutCompte::EN_ATTENTE->value);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('utilisateurs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('administrateur_id')->nullable();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique('utilisateurs_email_unique');
            $table->string('mot_de_passe_hash');
            $table->string('telephone')->nullable();
            $table->string('statut')->default(StatutCompte::EN_ATTENTE->value);
            $table->timestamp('derniere_connexion')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('administrateur_id', 'utilisateurs_administrateur_id_foreign')
                ->references('id')
                ->on('administrateurs')
                ->nullOnDelete();
        });

        Schema::create('candidats', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('utilisateur_id');
            $table->string('niveau')->nullable();
            $table->text('objectif_apprentissage')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('utilisateur_id', 'candidats_utilisateur_id_unique');
            $table->foreign('utilisateur_id', 'candidats_utilisateur_id_foreign')
                ->references('id')
                ->on('utilisateurs')
                ->restrictOnDelete();
        });

        Schema::create('formateurs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('utilisateur_id');
            $table->uuid('administrateur_validateur_id')->nullable();
            $table->string('specialite')->nullable();
            $table->text('biographie')->nullable();
            $table->string('statut_validation')->default(StatutCompte::EN_ATTENTE->value);
            $table->timestamps();
            $table->softDeletes();

            $table->unique('utilisateur_id', 'formateurs_utilisateur_id_unique');
            $table->foreign('utilisateur_id', 'formateurs_utilisateur_id_foreign')
                ->references('id')
                ->on('utilisateurs')
                ->restrictOnDelete();
            $table->foreign('administrateur_validateur_id', 'formateurs_admin_validateur_id_foreign')
                ->references('id')
                ->on('administrateurs')
                ->nullOnDelete();
        });

        Schema::create('justificatif_formateurs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('formateur_id');
            $table->uuid('administrateur_validateur_id')->nullable();
            $table->string('type');
            $table->string('fichier_url');
            $table->string('statut')->default(StatutJustificatif::EN_ATTENTE->value);
            $table->timestamp('date_depot')->nullable();
            $table->timestamp('date_validation')->nullable();
            $table->text('commentaire_validation')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('formateur_id', 'justificatifs_formateur_id_foreign')
                ->references('id')
                ->on('formateurs')
                ->restrictOnDelete();
            $table->foreign('administrateur_validateur_id', 'justificatifs_admin_validateur_id_foreign')
                ->references('id')
                ->on('administrateurs')
                ->nullOnDelete();
        });

        Schema::create('categories', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('parent_id')->nullable();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id', 'categories_parent_id_foreign')
                ->references('id')
                ->on('categories')
                ->nullOnDelete();
        });

        Schema::create('cours', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('categorie_id');
            $table->uuid('formateur_id');
            $table->uuid('administrateur_validateur_id')->nullable();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('niveau')->nullable();
            $table->string('langue')->default('fr');
            $table->decimal('prix', 10, 2)->default(0);
            $table->unsignedInteger('duree_estimee')->nullable();
            $table->string('image_url')->nullable();
            $table->string('statut')->default(StatutCours::BROUILLON->value);
            $table->timestamp('date_publication')->nullable();
            $table->text('motif_rejet')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('categorie_id', 'cours_categorie_id_foreign')
                ->references('id')
                ->on('categories')
                ->restrictOnDelete();
            $table->foreign('formateur_id', 'cours_formateur_id_foreign')
                ->references('id')
                ->on('formateurs')
                ->restrictOnDelete();
            $table->foreign('administrateur_validateur_id', 'cours_admin_validateur_id_foreign')
                ->references('id')
                ->on('administrateurs')
                ->nullOnDelete();
        });

        Schema::create('lecons', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('cours_id');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->unsignedInteger('ordre')->default(1);
            $table->unsignedInteger('duree_estimee')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cours_id', 'lecons_cours_id_foreign')
                ->references('id')
                ->on('cours')
                ->restrictOnDelete();
        });

        Schema::create('ressources', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('lecon_id');
            $table->string('titre');
            $table->string('type')->default(TypeRessource::DOCUMENT->value);
            $table->string('url');
            $table->unsignedInteger('ordre')->default(1);
            $table->boolean('telechargeable')->default(false);
            $table->unsignedBigInteger('taille')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lecon_id', 'ressources_lecon_id_foreign')
                ->references('id')
                ->on('lecons')
                ->restrictOnDelete();
        });

        Schema::create('inscriptions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('candidat_id');
            $table->uuid('cours_id');
            $table->timestamp('date_inscription')->nullable();
            $table->decimal('progression', 5, 2)->default(0);
            $table->string('statut')->default(StatutInscription::EN_COURS->value);
            $table->timestamp('date_fin')->nullable();
            $table->boolean('certificat_eligible')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['candidat_id', 'cours_id'], 'inscriptions_candidat_cours_unique');
            $table->foreign('candidat_id', 'inscriptions_candidat_id_foreign')
                ->references('id')
                ->on('candidats')
                ->restrictOnDelete();
            $table->foreign('cours_id', 'inscriptions_cours_id_foreign')
                ->references('id')
                ->on('cours')
                ->restrictOnDelete();
        });

        Schema::create('progression_lecons', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('inscription_id');
            $table->uuid('lecon_id');
            $table->boolean('terminee')->default(false);
            $table->timestamp('date_completion')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['inscription_id', 'lecon_id'], 'progression_lecons_inscription_lecon_unique');
            $table->foreign('inscription_id', 'progression_lecons_inscription_id_foreign')
                ->references('id')
                ->on('inscriptions')
                ->restrictOnDelete();
            $table->foreign('lecon_id', 'progression_lecons_lecon_id_foreign')
                ->references('id')
                ->on('lecons')
                ->restrictOnDelete();
        });

        Schema::create('evaluations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('cours_id');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('type_evaluation')->default(TypeEvaluation::QUIZ->value);
            $table->decimal('score_max', 8, 2)->default(100);
            $table->decimal('seuil_reussite', 8, 2)->default(50);
            $table->unsignedInteger('ordre')->default(1);
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cours_id', 'evaluations_cours_id_foreign')
                ->references('id')
                ->on('cours')
                ->restrictOnDelete();
        });

        Schema::create('questions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('evaluation_id');
            $table->text('enonce');
            $table->string('type')->default(TypeQuestion::QCM->value);
            $table->decimal('points', 8, 2)->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('evaluation_id', 'questions_evaluation_id_foreign')
                ->references('id')
                ->on('evaluations')
                ->restrictOnDelete();
        });

        Schema::create('option_reponses', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('question_id');
            $table->text('texte');
            $table->boolean('est_correcte')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('question_id', 'option_reponses_question_id_foreign')
                ->references('id')
                ->on('questions')
                ->restrictOnDelete();
        });

        Schema::create('critere_corrections', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('evaluation_id');
            $table->text('description');
            $table->decimal('poids', 8, 2)->default(1);
            $table->string('valeur_attendue')->nullable();
            $table->decimal('tolerance', 8, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('evaluation_id', 'criteres_evaluation_id_foreign')
                ->references('id')
                ->on('evaluations')
                ->restrictOnDelete();
        });

        Schema::create('soumission_evaluations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('candidat_id');
            $table->uuid('evaluation_id');
            $table->timestamp('date_debut')->nullable();
            $table->timestamp('date_soumission')->nullable();
            $table->unsignedInteger('numero_tentative')->default(1);
            $table->decimal('score_obtenu', 8, 2)->nullable();
            $table->boolean('reussi')->default(false);
            $table->string('statut')->default(StatutSoumission::SOUMISE->value);
            $table->text('feedback_automatique')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('candidat_id', 'soumissions_candidat_id_foreign')
                ->references('id')
                ->on('candidats')
                ->restrictOnDelete();
            $table->foreign('evaluation_id', 'soumissions_evaluation_id_foreign')
                ->references('id')
                ->on('evaluations')
                ->restrictOnDelete();
        });

        Schema::create('reponse_candidats', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('soumission_evaluation_id');
            $table->uuid('question_id');
            $table->text('valeur')->nullable();
            $table->boolean('est_correcte')->nullable();
            $table->decimal('points_obtenus', 8, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('soumission_evaluation_id', 'reponses_soumission_id_foreign')
                ->references('id')
                ->on('soumission_evaluations')
                ->restrictOnDelete();
            $table->foreign('question_id', 'reponses_question_id_foreign')
                ->references('id')
                ->on('questions')
                ->restrictOnDelete();
        });

        Schema::create('certificats', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('inscription_id');
            $table->string('code_verification');
            $table->timestamp('date_generation')->nullable();
            $table->string('fichier_url')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique('inscription_id', 'certificats_inscription_id_unique');
            $table->unique('code_verification', 'certificats_code_verification_unique');
            $table->foreign('inscription_id', 'certificats_inscription_id_foreign')
                ->references('id')
                ->on('inscriptions')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificats');
        Schema::dropIfExists('reponse_candidats');
        Schema::dropIfExists('soumission_evaluations');
        Schema::dropIfExists('critere_corrections');
        Schema::dropIfExists('option_reponses');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('progression_lecons');
        Schema::dropIfExists('inscriptions');
        Schema::dropIfExists('ressources');
        Schema::dropIfExists('lecons');
        Schema::dropIfExists('cours');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('justificatif_formateurs');
        Schema::dropIfExists('formateurs');
        Schema::dropIfExists('candidats');
        Schema::dropIfExists('utilisateurs');
        Schema::dropIfExists('administrateurs');
    }
};

