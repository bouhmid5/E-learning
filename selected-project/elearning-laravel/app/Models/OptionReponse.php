<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Database\Factories\OptionReponseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionReponse extends Model
{
    /** @use HasFactory<OptionReponseFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'option_reponses';

    protected $fillable = [
        'question_id',
        'texte',
        'est_correcte',
    ];

    protected function casts(): array
    {
        return [
            'est_correcte' => 'boolean',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
