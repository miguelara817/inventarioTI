<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['nombre'])]
class ActivoTipo extends Model
{
    use SoftDeletes;

    protected $table = 'activo_tipos';

    protected $primaryKey = 'activo_tipos_id';

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
        return $this->hasMany(Activo::class, 'activo_tipo_id', 'activo_tipos_id');
    }
}
