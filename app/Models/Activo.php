<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'codigo_activo',
    'activo_tipo_id',
    'marca',
    'modelo',
    'numero_serie',
    'ubicacion_id',
    'estado_asignado',
    'estado_operativo',
    'estado_fisico',
    'persona_asignada',
    'fecha_registro',
    'observaciones',
])]
class Activo extends Model
{
    use SoftDeletes;

    protected $table = 'activos';

    protected $primaryKey = 'activo_id';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_registro' => 'datetime',
        ];
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(ActivoTipo::class, 'activo_tipo_id', 'activo_tipos_id');
    }

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id', 'ubicacion_id');
    }

    public function estadoAsignado(): BelongsTo
    {
        return $this->belongsTo(ActivoEstadoAsignado::class, 'estado_asignado', 'activo_estado_asignado_id');
    }

    public function estadoOperativo(): BelongsTo
    {
        return $this->belongsTo(ActivoEstadoOperativo::class, 'estado_operativo', 'activo_estado_operativo_id');
    }

    public function estadoFisico(): BelongsTo
    {
        return $this->belongsTo(ActivoEstadoFisico::class, 'estado_fisico', 'activo_estado_fisico_id');
    }

    public function personaAsignada(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'persona_asignada', 'persona_id');
    }

    public function computadora(): HasOne
    {
        return $this->hasOne(Computadora::class, 'activo_id', 'activo_id');
    }

    public function dispositivoRed(): HasOne
    {
        return $this->hasOne(DispositivoRed::class, 'activo_id', 'activo_id');
    }
}
