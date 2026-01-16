<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ProductImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_import_page()
    {
        $response = $this->get('/products/import');
        $response->assertStatus(200);
        $response->assertSee('Importar Productes');
    }

    public function test_can_import_products()
    {
        Excel::fake();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/products/import', [
            'file' => UploadedFile::fake()->create('products.xlsx')
        ]);

        $response->assertStatus(302);
        
        // Since we are faking Excel, the actual import logic won't run unless we use a real file or mock the import call.
        // But we can assert that the import method was called.
        Excel::assertImported('products.xlsx');
    }
}
