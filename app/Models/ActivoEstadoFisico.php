<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['valor'])]
class ActivoEstadoFisico extends Model
{
    use SoftDeletes;

    protected $table = 'activo_estado_fisico';

    protected $primaryKey = 'activo_estado_fisico_id';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    public function activos(): HasMany
    {
        return $this->hasMany(Activo::class, 'estado_fisico', 'activo_estado_fisico_id');
    }
}
