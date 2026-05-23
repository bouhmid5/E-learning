<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdministrateurSeeder::class,
            UtilisateurSeeder::class,
            CandidatSeeder::class,
            FormateurSeeder::class,
            JustificatifFormateurSeeder::class,
            CategorieSeeder::class,
            CoursSeeder::class,
            LeconSeeder::class,
            RessourceSeeder::class,
            InscriptionSeeder::class,
            ProgressionLeconSeeder::class,
            EvaluationSeeder::class,
            QuestionSeeder::class,
            OptionReponseSeeder::class,
            CritereCorrectionSeeder::class,
            SoumissionEvaluationSeeder::class,
            ReponseCandidatSeeder::class,
            CertificatSeeder::class,
        ]);
    }
}
