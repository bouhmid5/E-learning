<?php

namespace App\Models;

use App\Enums\StatutJustificatif;
use App\Models\Concerns\UsesUuid;
use Database\Factories\JustificatifFormateurFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JustificatifFormateur extends Model
{
    /** @use HasFactory<JustificatifFormateurFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'justificatif_formateurs';

    protected $fillable = [
        'formateur_id',
        'administrateur_validateur_id',
        'type',
        'fichier_url',
        'statut',
        'date_depot',
        'date_validation',
        'commentaire_validation',
    ];

    protected function casts(): array
    {
        return [
            'statut' => StatutJustificatif::class,
            'date_depot' => 'datetime',
            'date_validation' => 'datetime',
        ];
    }

    public function formateur(): BelongsTo
    {
        return $this->belongsTo(Formateur::class);
    }

    public function administrateurValidateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class, 'administrateur_validateur_id');
    }
}
