<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class Reserva extends Model
{
    protected $fillable = [
        'user_id',
        'espacio_id',
        'fecha',
        'franja',
        'estado',
        'notas',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function espacio(): BelongsTo
    {
        return $this->belongsTo(Espacio::class);
    }

    /**
     * Regla de negocio en el servidor, no solo en el formulario.
     * El unique de la migración es la red de seguridad; esto da el mensaje claro.
     */
    public static function assertDisponible(int $espacioId, string $fecha, string $franja, ?int $ignorarId = null): void
    {
        $existe = static::query()
            ->where('espacio_id', $espacioId)
            ->whereDate('fecha', $fecha)
            ->where('franja', $franja)
            ->when($ignorarId, fn ($q) => $q->where('id', '!=', $ignorarId))
            ->exists();

        if ($existe) {
            throw ValidationException::withMessages([
                'franja' => 'Ese horario ya está ocupado.',
            ]);
        }
    }
}
