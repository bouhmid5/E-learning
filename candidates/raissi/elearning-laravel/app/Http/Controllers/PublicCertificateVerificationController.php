<?php

namespace App\Http\Controllers;

use App\Models\Certificat;
use Illuminate\View\View;

class PublicCertificateVerificationController extends Controller
{
    public function show(string $codeVerification): View
    {
        $certificat = Certificat::query()
            ->with('inscription.candidat.utilisateur', 'inscription.cours')
            ->where('code_verification', $codeVerification)
            ->first();

        return view('certificates.verify', [
            'certificat' => $certificat,
            'codeVerification' => $codeVerification,
        ]);
    }
}

