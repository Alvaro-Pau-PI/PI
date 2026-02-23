<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            'SKU',
            'NOM',
            'DESCRIPCIO',
            'PREU',
            'ESTOC',
            'IMG',
            'IMATGES_EXTRA', // Nueva cabecera
        ];
    }

    public function map($product): array
    {
        // Convertir el array de imágenes a un string separado por comas
        $extraImagesStr = '';
        if (is_array($product->images) && count($product->images) > 0) {
            $extraImagesStr = implode(',', $product->images);
        }

        return [
            $product->sku,
            $product->name,
            $product->description,
            $product->price,
            $product->stock,
            $product->image,
            $extraImagesStr, // Nueva columna mapeada
        ];
    }
}
