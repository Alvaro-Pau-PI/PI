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

        $product->name = $row['name'];
        $product->description = $row['description'] ?? null;
        $product->price = $row['price'];
        $product->stock = $row['stock'];
        $product->category = $row['category'] ?? null;
        
        // Si la imagen se proporciona en el excel, guardarla. 
        // Nota: Esto espera una cadena de ruta, no el manejo real de subida de archivos dentro del excel por ahora.
        if (isset($row['image'])) {
            $product->image = $row['image'];
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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            // 'image' y 'category' son opcionales
        ];
    }
}
