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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_producto');
            $table->integer('categoria_id');
            $table->integer('proveedor_id');
            $table->string('codigo_producto')->nullable();
            $table->string('almacen_producto')->nullable();
            $table->string('imagen_producto')->nullable();
            $table->integer('tienda_producto')->nullable();
            $table->date('fecha_compra')->nullable();
            $table->date('fecha_expiracion')->nullable();
            $table->decimal('precio_compra', 10, 2)->nullable();
            $table->decimal('precio_venta', 10, 2)->nullable();
            
            $table->timestamps(); // mantiene created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
