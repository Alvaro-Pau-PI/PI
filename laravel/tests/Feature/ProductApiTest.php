<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test GET /api/products returns 200 and a list of products.
     */
    public function test_can_list_products()
    {
        // Get initial count from seeder
        $initialCount = Product::count();

        // Arrange: Create some products
        Product::factory()->count(3)->create();

        // Act: Hit the endpoint
        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonCount($initialCount + 3);
    }

    /**
     * Test GET /api/products returns correct JSON structure.
     */
    public function test_products_have_correct_structure()
    {
        Product::factory()->create([
            'name' => 'Test Product',
            'price' => 99.99
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Test Product',
                     'price' => '99.99' 
                 ]);
    }
}
