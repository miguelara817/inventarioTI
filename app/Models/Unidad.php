<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['nombre'])]
class Unidad extends Model
{
    use SoftDeletes;

    protected $table = 'unidades';

    protected $primaryKey = 'unidad_id';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    public function personas(): HasMany
    {
        return $this->hasMany(Persona::class, 'unidad_id', 'unidad_id');
    }
}
