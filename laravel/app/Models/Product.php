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
        'eco_score',
        'is_refurbished',
        'is_recyclable',
        'has_eco_packaging',
        'is_local_supplier',
        'carbon_footprint',
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
            'eco_score' => 'integer',
            'is_refurbished' => 'boolean',
            'is_recyclable' => 'boolean',
            'has_eco_packaging' => 'boolean',
            'is_local_supplier' => 'boolean',
            'carbon_footprint' => 'decimal:2',
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

    /**
     * Relación: Un producto tiene muchas reseñas/reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Scope: Filtra productos sostenibles (eco_score >= umbral)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $minScore Puntuación mínima de eco_score (por defecto 70)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSustainable($query, int $minScore = 70)
    {
        return $query->where('eco_score', '>=', $minScore);
    }

    /**
     * Accessor: Genera etiqueta visual de sostenibilidad
     * 
     * @return string|null
     */
    public function getEcoLabelAttribute(): ?string
    {
        if ($this->eco_score >= 80) {
            return '🌿 Excelente';
        } elseif ($this->eco_score >= 70) {
            return '♻️ Muy Bueno';
        } elseif ($this->eco_score >= 50) {
            return '🌱 Bueno';
        } elseif ($this->eco_score > 0) {
            return '⚡ Básico';
        }
        
        return null;
    }

    /**
     * Verifica si el producto es considerado sostenible
     * 
     * @return bool
     */
    public function isSustainable(): bool
    {
        return $this->eco_score >= 70 
            || $this->is_refurbished 
            || ($this->is_recyclable && $this->has_eco_packaging);
    }

    /**
     * Calcula automáticamente el eco_score basado en criterios
     * Este método puede ser llamado antes de guardar el producto
     * 
     * @return int
     */
    public function calculateEcoScore(): int
    {
        $score = 0;

        // Reacondicionado: gran impacto positivo (+40 puntos)
        if ($this->is_refurbished) {
            $score += 40;
        }

        // Embalaje eco (+15 puntos)
        if ($this->has_eco_packaging) {
            $score += 15;
        }

        // Reciclable (+20 puntos)
        if ($this->is_recyclable) {
            $score += 20;
        }

        // Proveedor local: reduce transporte (+15 puntos)
        if ($this->is_local_supplier) {
            $score += 15;
        }

        // Huella de carbono baja (+10 puntos si < 5kg CO2)
        if ($this->carbon_footprint !== null && $this->carbon_footprint < 5) {
            $score += 10;
        }

        // Máximo 100
        return min($score, 100);
    }
}
