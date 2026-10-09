<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('espacios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo'); // salon, bbq, piscina
            $table->unsignedInteger('cupo');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('espacio_id')->constrained()->cascadeOnDelete();
            $table->date('fecha');
            $table->string('franja'); // manana, tarde, noche
            $table->string('estado')->default('pendiente'); // pendiente, confirmada, rechazada
            $table->text('notas')->nullable();
            $table->timestamps();

            // Una sola reserva por espacio, fecha y franja.
            $table->unique(['espacio_id', 'fecha', 'franja']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
        Schema::dropIfExists('espacios');
    }
};
