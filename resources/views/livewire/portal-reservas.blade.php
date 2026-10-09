<div class="mx-auto max-w-3xl space-y-6 p-6">
    <header class="space-y-1">
        <h1 class="text-2xl font-semibold text-slate-900">Reservar un espacio</h1>
        <p class="text-sm text-slate-600">Elige el día y una franja libre. El botón dice la acción, no "Aceptar".</p>
    </header>

    @if (session('ok'))
        <p class="rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('ok') }}</p>
    @endif

    <label class="block text-sm font-medium text-slate-700">
        Fecha
        <input type="date" wire:model.live="fecha" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
    </label>

    <div class="space-y-4">
        @foreach ($espacios as $espacio)
            <article class="rounded-xl border border-slate-200 p-4">
                <h2 class="font-medium text-slate-900">{{ $espacio->nombre }}</h2>
                <p class="text-sm text-slate-500">{{ ucfirst($espacio->tipo) }} · cupo {{ $espacio->cupo }}</p>

                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach (['manana' => 'Mañana', 'tarde' => 'Tarde', 'noche' => 'Noche'] as $clave => $label)
                        @php
                            $ocupada = $espacio->reservas->contains('franja', $clave);
                        @endphp
                        <button
                            type="button"
                            wire:click="reservar({{ $espacio->id }}, '{{ $clave }}')"
                            @disabled($ocupada)
                            class="rounded-lg px-3 py-2 text-sm font-medium
                                {{ $ocupada
                                    ? 'cursor-not-allowed bg-slate-100 text-slate-400'
                                    : 'bg-blue-600 text-white hover:bg-blue-700' }}"
                        >
                            {{ $ocupada ? $label . ' ocupada' : 'Reservar ' . strtolower($label) }}
                        </button>
                    @endforeach
                </div>
            </article>
        @endforeach
    </div>
</div>
