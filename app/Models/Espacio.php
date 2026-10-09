<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Espacio extends Model
{
    protected $fillable = ['nombre', 'tipo', 'cupo', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
}
