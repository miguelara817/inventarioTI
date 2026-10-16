<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'activo_id',
    'hostname',
    'procesador',
    'memoria_ram',
    'almacenamiento',
    'video',
    'direccion_ip',
    'direccion_mac',
    'sistema_operativo',
    'version_so',
])]
class Computadora extends Model
{
    use SoftDeletes;

    protected $table = 'computadoras';

    protected $primaryKey = 'computadora_id';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    public function activo(): BelongsTo
    {
        return $this->belongsTo(Activo::class, 'activo_id', 'activo_id');
    }
}
