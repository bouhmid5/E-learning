<?php

namespace App\Models;

use App\Enums\StatutCompte;
use App\Models\Concerns\UsesUuid;
use Database\Factories\AdministrateurFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrateur extends Authenticatable
{
    /** @use HasFactory<AdministrateurFactory> */
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'administrateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe_hash',
        'niveau_acces',
        'statut',
    ];

    protected $hidden = [
        'mot_de_passe_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'statut' => StatutCompte::class,
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'mot_de_passe_hash';
    }

    public function getAuthPassword(): string
    {
        return (string) $this->mot_de_passe_hash;
    }

    public function utilisateursGeres(): HasMany
    {
        return $this->hasMany(Utilisateur::class);
    }

    public function justificatifsValides(): HasMany
    {
        return $this->hasMany(JustificatifFormateur::class, 'administrateur_validateur_id');
    }

    public function formateursValides(): HasMany
    {
        return $this->hasMany(Formateur::class, 'administrateur_validateur_id');
    }

    public function coursValides(): HasMany
    {
        return $this->hasMany(Cours::class, 'administrateur_validateur_id');
    }
}

