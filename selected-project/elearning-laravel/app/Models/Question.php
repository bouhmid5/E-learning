<?php

namespace App\Models;

use App\Enums\TypeQuestion;
use App\Models\Concerns\UsesUuid;
use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    /** @use HasFactory<QuestionFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'questions';

    protected $fillable = [
        'evaluation_id',
        'enonce',
        'type',
        'points',
    ];

    protected function casts(): array
    {
        return [
            'type' => TypeQuestion::class,
            'points' => 'decimal:2',
        ];
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function optionsReponse(): HasMany
    {
        return $this->hasMany(OptionReponse::class);
    }

    public function reponsesCandidats(): HasMany
    {
        return $this->hasMany(ReponseCandidat::class);
    }
}
