<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return Product::updateOrCreate(
            ['sku' => $row['sku']],
            [
                'name'        => $row['nom'],
                'description' => $row['descripcio'] ?? null,
                'price'       => $row['preu'],
                'stock'       => $row['estoc'],
                'image'       => $row['img'] ?? null,
                'category'    => $row['category'] ?? null,
            ]
        );
    }

    public function rules(): array
    {
        return [
            'sku'   => 'required|string|max:50',
            'nom'   => 'required|string|max:255',
            'preu'  => 'required|numeric|min:0',
            'estoc' => 'required|integer|min:0',
        ];
    }
}
