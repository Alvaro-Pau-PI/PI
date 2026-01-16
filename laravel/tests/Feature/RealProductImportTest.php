<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class RealProductImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_import_products_with_spanish_headers()
    {
        // Create a temporary CSV file with Spanish headers
        $header = 'SKU,NOM,DESCRIPCIO,PREU,ESTOC,IMG';
        $row1 = 'TEST-SKU-001,Test Product 1,Description 1,10.50,100,img1.jpg';
        $row2 = 'TEST-SKU-002,Test Product 2,Description 2,20.00,50,img2.jpg';
        
        $content = implode("\n", [$header, $row1, $row2]);
        
        $file = UploadedFile::fake()->createWithContent('products_spanish.csv', $content);

        $user = User::factory()->create();

        // Perform the import
        $response = $this->actingAs($user)->post('/products/import', [
            'file' => $file
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Assert database has the products
        $this->assertDatabaseHas('products', [
            'sku' => 'TEST-SKU-001',
            'name' => 'Test Product 1',
            'price' => 10.50,
            'stock' => 100,
        ]);

        $this->assertDatabaseHas('products', [
            'sku' => 'TEST-SKU-002',
            'name' => 'Test Product 2',
            'price' => 20.00,
            'stock' => 50,
        ]);
    }
}
