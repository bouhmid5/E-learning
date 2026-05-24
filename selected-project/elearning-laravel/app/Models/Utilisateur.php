<?php

namespace App\Models;

use App\Enums\StatutCompte;
use App\Models\Concerns\UsesUuid;
use Database\Factories\UtilisateurFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    /** @use HasFactory<UtilisateurFactory> */
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'administrateur_id',
        'nom',
        'prenom',
        'email',
        'mot_de_passe_hash',
        'telephone',
        'statut',
        'derniere_connexion',
    ];

    protected $hidden = [
        'mot_de_passe_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'statut' => StatutCompte::class,
            'derniere_connexion' => 'datetime',
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

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class);
    }

    public function candidat(): HasOne
    {
        return $this->hasOne(Candidat::class);
    }

    public function formateur(): HasOne
    {
        return $this->hasOne(Formateur::class);
    }
}

