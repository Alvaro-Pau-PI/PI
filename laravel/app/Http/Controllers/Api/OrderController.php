<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controlador de Pedidos.
 * Gestiona la creación, consulta y administración de pedidos.
 */
class OrderController extends Controller
{
    /**
     * Crear un nuevo pedido (usuario autenticado).
     * Valida los datos, crea el pedido con sus items y descuenta stock.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:255',
            'shipping_postal' => 'required|string|max:10',
            'shipping_phone' => 'required|string|max:20',
            'card_last_four' => 'required|string|size:4',
            'payment_method' => 'sometimes|string|in:card',
            'notes' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $subtotal = 0;
            $orderItems = [];

            // Verificar stock y calcular subtotal
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'message' => "Stock insuficient per a {$product->name}. Disponible: {$product->stock}"
                    ], 422);
                }

                $itemTotal = $product->price * $item['quantity'];
                $subtotal += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                ];

                // Descontar stock
                $product->decrement('stock', $item['quantity']);
            }

            $tax = round($subtotal * 0.21, 2);
            $total = round($subtotal + $tax, 2);

            // Crear pedido
            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $validated['payment_method'] ?? 'card',
                'card_last_four' => $validated['card_last_four'],
                'shipping_name' => $validated['shipping_name'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_postal' => $validated['shipping_postal'],
                'shipping_phone' => $validated['shipping_phone'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Crear items del pedido
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            return response()->json(
                $order->load('items'),
                201
            );
        });
    }

    /**
     * Listar pedidos del usuario autenticado.
     */
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    /**
     * Ver detalle de un pedido del usuario.
     */
    public function show(Request $request, $id)
    {
        $order = Order::where('user_id', $request->user()->id)
            ->with('items')
            ->findOrFail($id);

        return response()->json($order);
    }

    /**
     * [ADMIN] Listar todos los pedidos con filtros.
     */
    public function adminIndex(Request $request)
    {
        $query = Order::with(['items', 'user:id,name,email']);

        // Filtrar por estado si se envía
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Buscar por número de pedido
        if ($request->has('search') && $request->search) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($orders);
    }

    /**
     * [ADMIN] Actualizar el estado de un pedido.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,confirmed,shipped,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $validated['status']]);

        return response()->json($order->load('items'));
    }
}
