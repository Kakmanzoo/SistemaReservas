<?php

namespace App\Livewire;

use App\Models\Espacio;
use App\Models\Reserva;
use livewire\Attributes\layout;
use Livewire\Component;

#[Layout('layouts.portal')]
class PortalReservas extends Component
{
    public string $fecha = '';

    public function mount(): void
    {
        $this->fecha = now()->toDateString();
    }

    public function render()
    {
        $espacios = Espacio::query()
            ->where('activo', true)
            ->with(['reservas' => fn ($q) => $q->whereDate('fecha', $this->fecha)])
            ->orderBy('nombre')
            ->get();

        return view('livewire.portal-reservas', [
            'espacios' => $espacios,
        ]);
    }

    public function reservar(int $espacioId, string $franja): void
    {
        Reserva::assertDisponible($espacioId, $this->fecha, $franja);

        Reserva::create([
            'user_id' => auth()->id(),
            'espacio_id' => $espacioId,
            'fecha' => $this->fecha,
            'franja' => $franja,
            'estado' => 'pendiente',
        ]);

        session()->flash('ok', 'Reserva enviada. Queda pendiente de confirmación.');
    }
}
