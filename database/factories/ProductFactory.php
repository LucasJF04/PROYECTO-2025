<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Producto;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_producto' => $this->faker->word(),
            'categoria_id' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'proveedor_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
            'almacen_producto' => $this->faker->randomLetter(), // ejemplo: A, B, C
            'tienda_producto' => $this->faker->randomNumber(3),
            'precio_compra' => $this->faker->randomFloat(2, 10, 100),
            'precio_venta' => $this->faker->randomFloat(2, 20, 200),
            'fecha_compra' => Carbon::now(),
            'fecha_expiracion' => Carbon::now()->addYears(2),
            'imagen_producto' => 'default.png', // si quieres un valor por defecto
        ];
    }
}
