<?php

namespace App\Models;

use App\Enums\StatutInscription;
use App\Models\Concerns\UsesUuid;
use Database\Factories\InscriptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inscription extends Model
{
    /** @use HasFactory<InscriptionFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'inscriptions';

    protected $fillable = [
        'candidat_id',
        'cours_id',
        'date_inscription',
        'progression',
        'statut',
        'date_fin',
        'certificat_eligible',
    ];

    protected function casts(): array
    {
        return [
            'date_inscription' => 'datetime',
            'progression' => 'decimal:2',
            'statut' => StatutInscription::class,
            'date_fin' => 'datetime',
            'certificat_eligible' => 'boolean',
        ];
    }

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidat::class);
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    public function progressionsLecons(): HasMany
    {
        return $this->hasMany(ProgressionLecon::class);
    }

    public function certificat(): HasOne
    {
        return $this->hasOne(Certificat::class);
    }
}
