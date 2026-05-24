<?php

namespace App\Services\Certificates;

use App\Models\Certificat;
use App\Models\Inscription;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateGenerationService
{
    public function __construct(private readonly CertificateEligibilityService $eligibility)
    {
    }

    public function generate(Inscription $inscription): Certificat
    {
        return DB::transaction(function () use ($inscription): Certificat {
            $lockedInscription = Inscription::query()
                ->whereKey($inscription->id)
                ->lockForUpdate()
                ->firstOrFail();

            $existing = $lockedInscription->certificat()->first();

            if ($existing) {
                return $existing;
            }

            if (! $this->eligibility->isEligible($lockedInscription)) {
                throw new DomainException('Les conditions de certification ne sont pas remplies.');
            }

            $code = $this->uniqueVerificationCode();
            $path = "certificates/{$code}.txt";

            Storage::disk('public')->put($path, $this->certificateContent($lockedInscription, $code));

            return Certificat::create([
                'inscription_id' => $lockedInscription->id,
                'code_verification' => $code,
                'date_generation' => now(),
                'fichier_url' => $path,
                'actif' => true,
            ]);
        });
    }

    private function uniqueVerificationCode(): string
    {
        do {
            $code = 'CERT-'.now()->format('Ymd').'-'.Str::upper(Str::random(10));
        } while (Certificat::query()->where('code_verification', $code)->exists());

        return $code;
    }

    private function certificateContent(Inscription $inscription, string $code): string
    {
        $inscription->loadMissing('candidat.utilisateur', 'cours');
        $candidateName = trim($inscription->candidat->utilisateur->prenom.' '.$inscription->candidat->utilisateur->nom);

        return implode(PHP_EOL, [
            'Certificat de réussite',
            "Candidat: {$candidateName}",
            "Cours: {$inscription->cours->titre}",
            "Code de vérification: {$code}",
            'Date de génération: '.now()->toDateString(),
        ]);
    }
}

