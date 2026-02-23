<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Buscar producto existente por SKU o crear uno nuevo
        $product = Product::firstOrNew(['sku' => $row['sku']]);

        $product->name = $row['nom'] ?? $row['name'] ?? null;
        $product->description = $row['descripcio'] ?? $row['description'] ?? null;
        $product->price = $row['preu'] ?? $row['price'] ?? 0;
        $product->stock = $row['estoc'] ?? $row['stock'] ?? 0;
        $product->category = $row['categoria'] ?? $row['category'] ?? null;
        
        $img = $row['img'] ?? $row['image'] ?? null;
        if ($img) {
            $product->image = $img;
        }
        
        // Asignar categoría automáticamente si falta y es posible
        if (!$product->category) {
            $product->category = $product->getCategoryFromSku();
        }

        $product->save();

        return $product;
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|string|max:50',
            'nom' => 'required_without:name|string|max:255',
            'preu' => 'required_without:price|numeric|min:0',
            'estoc' => 'required_without:stock|integer|min:0',
        ];
    }
}
