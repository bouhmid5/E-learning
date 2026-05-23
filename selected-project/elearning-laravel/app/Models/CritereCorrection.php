<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Database\Factories\CritereCorrectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CritereCorrection extends Model
{
    /** @use HasFactory<CritereCorrectionFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'critere_corrections';

    protected $fillable = [
        'evaluation_id',
        'description',
        'poids',
        'valeur_attendue',
        'tolerance',
    ];

    protected function casts(): array
    {
        return [
            'poids' => 'decimal:2',
            'tolerance' => 'decimal:2',
        ];
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }
}
