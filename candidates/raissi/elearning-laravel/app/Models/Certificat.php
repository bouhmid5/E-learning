<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Database\Factories\CertificatFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificat extends Model
{
    /** @use HasFactory<CertificatFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'certificats';

    protected $fillable = [
        'inscription_id',
        'code_verification',
        'date_generation',
        'fichier_url',
        'actif',
    ];

    protected function casts(): array
    {
        return [
            'date_generation' => 'datetime',
            'actif' => 'boolean',
        ];
    }

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }
}
