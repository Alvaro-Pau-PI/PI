<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    /**
     * Derive category from SKU prefix if not set.
     * Categories: CPU, GPU, MB (Motherboard), RAM, SSD, PSU, CASE, COOL
     */
    public function getCategoryFromSku(): ?string
    {
        if (!$this->sku) {
            return null;
        }
        
        $prefix = explode('-', $this->sku)[0] ?? null;
        
        return match($prefix) {
            'CPU' => 'Processadors',
            'GPU' => 'Targetes Gràfiques',
            'MB' => 'Plaques Base',
            'RAM' => 'Memòria RAM',
            'SSD' => 'Emmagatzematge',
            'PSU' => 'Fonts Alimentació',
            'CASE' => 'Caixes',
            'COOL' => 'Refrigeració',
            default => 'Altres',
        };
    }
}
