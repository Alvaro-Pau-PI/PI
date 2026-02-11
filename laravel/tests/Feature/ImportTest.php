<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test import page can be viewed by authenticated user.
     */
    public function test_import_page_is_accessible()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('products.import.show'));

        $response->assertStatus(200);
    }

    /**
     * Test file upload triggers Excel import.
     */
    public function test_can_upload_excel_file()
    {
        Excel::fake();
        
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('products.xlsx');

        $response = $this->actingAs($user)->post(route('products.import.store'), [
            'file' => $file
        ]);

        // Assert redirected back with success
        $response->assertSessionHas('success');
        
        // Assert import was queued
        Excel::assertImported('products.xlsx', function(ProductsImport $import) {
            return true;
        });
    }

    /**
     * Test validation fails if no file.
     */
    public function test_import_validation_fails_without_file()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('products.import.store'), []);

        $response->assertSessionHasErrors(['file']);
    }
}
