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
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/products/import');
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
        
        // Dado que estamos falseando Excel, la lógica de importación real no se ejecutará a menos que usemos un archivo real o imitemos la llamada de importación.
        // Pero podemos afirmar que se llamó al método de importación.
        Excel::assertImported('products.xlsx');
    }
}
