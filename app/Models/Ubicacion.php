<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['nombre', 'edificio', 'piso'])]
class Ubicacion extends Model
{
    use SoftDeletes;

    protected $table = 'ubicaciones';

    protected $primaryKey = 'ubicacion_id';

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
        return $this->hasMany(Activo::class, 'ubicacion_id', 'ubicacion_id');
    }
}
