<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'CPU' => ['Processadors', 'Procesador de alto rendimiento'],
            'GPU' => ['Targetes Gràfiques', 'Tarjeta gráfica para gaming'],
            'MB' => ['Plaques Base', 'Placa base con últimas tecnologías'],
            'RAM' => ['Memòria RAM', 'Kit de memoria de alta velocidad'],
            'SSD' => ['Emmagatzematge', 'Almacenamiento SSD de alta velocidad'],
            'PSU' => ['Fonts Alimentació', 'Fuente de alimentación certificada'],
            'CASE' => ['Caixes', 'Caja con excelente flujo de aire'],
            'COOL' => ['Refrigeració', 'Sistema de refrigeración silencioso'],
        ];
        
        $prefix = fake()->randomElement(array_keys($categories));
        $categoryData = $categories[$prefix];
        
        return [
            'sku' => $prefix . '-' . strtoupper(fake()->bothify('???-####')),
            'name' => fake()->words(4, true),
            'description' => $categoryData[1] . '. ' . fake()->sentence(),
            'price' => fake()->randomFloat(2, 29.99, 1999.99),
            'stock' => fake()->numberBetween(0, 100),
            'image' => 'img/productos/placeholder.png',
            'category' => $categoryData[0],
        ];
    }

    /**
     * State for out of stock products.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * State for expensive products.
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => fake()->randomFloat(2, 1000, 2500),
        ]);
    }
}
