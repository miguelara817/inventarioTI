<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['nombre', 'apellido', 'unidad_id', 'is_active'])]
class Persona extends Model
{
    use SoftDeletes;

    protected $table = 'personas';

    protected $primaryKey = 'persona_id';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'unidad_id', 'unidad_id');
    }

    public function activos(): HasMany
    {
        return $this->hasMany(Activo::class, 'persona_asignada', 'persona_id');
    }
}
