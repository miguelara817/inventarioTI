<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'activo_id',
    'hostname',
    'direccion_ip',
    'direccion_mac',
])]
class DispositivoRed extends Model
{
    use SoftDeletes;

    protected $table = 'dispositivos_red';

    protected $primaryKey = 'dispositivos_red_id';

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
