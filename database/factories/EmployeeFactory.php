<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empleado>
 */
class EmpleadoFactory extends Factory
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
            'experiencia' => fake()->randomElement(['0 Año', '1 Año', '2 Años', '3 Años', '4 Años', '5 Años']),
            'salario' => fake()->randomNumber(5, true),
            'vacaciones' => fake()->randomElement(['0', '5', '10', '15']),
            'ciudad' => fake()->city(),
            'foto' => null, // opcional, puedes generar alguna imagen si quieres
        ];
    }
}
