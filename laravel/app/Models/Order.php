<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Order - Representa un pedido de un usuario.
 * Estados posibles: pending, confirmed, shipped, delivered, cancelled
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'tax',
        'total',
        'payment_method',
        'card_last_four',
        'shipping_name',
        'shipping_address',
        'shipping_city',
        'shipping_postal',
        'shipping_phone',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Relación: El pedido pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un pedido tiene muchos items.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generar un número de pedido único.
     */
    public static function generateOrderNumber(): string
    {
        // Formato: AP-YYYYMMDD-XXXXX (AP = AlberoPerez)
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -5));
        return "AP-{$date}-{$random}";
    }
}
