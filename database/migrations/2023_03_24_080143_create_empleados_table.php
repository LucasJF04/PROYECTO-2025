<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->string('telefono')->unique();
            $table->text('direccion')->nullable();
            $table->string('experiencia')->nullable();
            $table->string('foto')->nullable();
            $table->integer('salario')->nullable();
            $table->string('vacaciones')->nullable();
            $table->string('ciudad')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
