<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->string('telefono')->unique();
            $table->text('direccion')->nullable();
            $table->string('nombre_tienda')->nullable();
            $table->string('foto')->nullable();
            $table->string('tipo')->nullable();
            $table->string('titular_cuenta')->nullable();
            $table->string('numero_cuenta')->nullable();
            $table->string('banco')->nullable();
            $table->string('sucursal')->nullable();
            $table->string('ciudad')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
