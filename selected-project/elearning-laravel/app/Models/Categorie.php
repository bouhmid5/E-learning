<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Database\Factories\CategorieFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model
{
    /** @use HasFactory<CategorieFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'categories';

    protected $fillable = [
        'parent_id',
        'nom',
        'description',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'parent_id');
    }

    public function enfants(): HasMany
    {
        return $this->hasMany(Categorie::class, 'parent_id');
    }

    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class);
    }
}
