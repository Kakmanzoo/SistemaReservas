<?php

namespace Database\Seeders;

use App\Models\Espacio;
use Illuminate\Database\Seeder;

class EspacioSeeder extends Seeder
{
    public function run(): void
    {
        $espacios = [
            ['nombre' => 'Salón social', 'tipo' => 'salon', 'cupo' => 40],
            ['nombre' => 'Zona BBQ', 'tipo' => 'bbq', 'cupo' => 15],
            ['nombre' => 'Piscina', 'tipo' => 'piscina', 'cupo' => 20],
        ];

        foreach ($espacios as $espacio) {
            Espacio::query()->updateOrCreate(
                ['nombre' => $espacio['nombre']],
                $espacio + ['activo' => true],
            );
        }
    }
}
