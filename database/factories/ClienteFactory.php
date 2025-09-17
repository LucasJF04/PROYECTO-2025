<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),
            'correo' => fake()->unique()->safeEmail(),
            'telefono' => fake()->unique()->phoneNumber(),
            'direccion' => fake()->address(),
            'nombre_tienda' => fake()->company(),
            'titular_cuenta' => fake()->name(),
            'numero_cuenta' => fake()->randomNumber(8, true),
            'nombre_banco' => fake()->randomElement(['BRI', 'BNI', 'BCA', 'BSI', 'MANDIRI', 'BJB']),
            'sucursal_banco' => fake()->city(),
            'ciudad' => fake()->city(),
        ];
    }
}
