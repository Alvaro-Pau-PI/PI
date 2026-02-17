<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

/**
 * @group Products
 *
 * APIs for managing products
 */
class ProductController extends Controller
{
    /**
     * Mostrar listado del recurso.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }


    /**
     * Get all products
     *
     * Returns a list of all products available in the system.
     *
     * @response {
     *  "id": 1,
     *  "sku": "CPU-INT-001",
     *  "name": "Intel Core i9",
     *  "description": "High performance processor",
     *  "price": "499.99",
     *  "stock": 100,
     *  "image": "/images/cpu.jpg",
     *  "category": "Processadors",
     *  "created_at": "2023-01-01T12:00:00.000000Z",
     *  "updated_at": "2023-01-01T12:00:00.000000Z"
     * }
     */
    /**
     * Get all products with filters and pagination
     *
     * Returns a paginated list of products, optionally filtered by search term, category, and price range.
     *
     * @queryParam search string filter by name or description. Example: "Intel"
     * @queryParam category string filter by category name. Example: "Processadors"
     * @queryParam min_price number filter by minimum price. Example: 100
     * @queryParam max_price number filter by maximum price. Example: 500
     * @queryParam page integer page number. Example: 1
     */
    public function apiIndex(Request $request)
    {
        $query = Product::query();

        // Búsqueda por texto (nombre o descripción)
        $query->when($request->input('search'), function ($q, $search) {
            return $q->where(function ($subQ) use ($search) {
                $subQ->where('name', 'like', "%{$search}%")
                     ->orWhere('description', 'like', "%{$search}%");
            });
        });

        // Filtro por categoría
        $query->when($request->input('category'), function ($q, $category) {
            return $q->where('category', $category);
        });

        // Filtro por precio mínimo
        $query->when($request->input('min_price'), function ($q, $min) {
            return $q->where('price', '>=', $min);
        });

        // Filtro por precio máximo
        $query->when($request->input('max_price'), function ($q, $max) {
            return $q->where('price', '<=', $max);
        });

        // Paginación de 12 elementos por página
        return response()->json($query->paginate(12));
    }

    /**
     * Mostrar el recurso especificado.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }


    /**
     * Get a specific product
     *
     * Returns the details of a specific product by ID.
     *
     * @urlParam product integer required The ID of the product. Example: 1
     */
    public function apiShow(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Get featured products (most popular/best rated)
     *
     * Returns a list of featured products based on ratings and review count.
     * This uses an intelligent algorithm combining average rating with review count.
     *
     * @queryParam limit integer Number of products to return. Defaults to 6. Example: 4
     * 
     * @response [{
     *  "id": 1,
     *  "name": "RTX 4090",
     *  "category": "Targetes Gràfiques",
     *  "price": "1899.99",
     *  "image": "/images/rtx4090.jpg",
     *  "rating_avg": 4.8,
     *  "review_count": 24
     * }]
     */
    public function featured(Request $request)
    {
        $limit = $request->input('limit', 6);

        // Obtener productos con rating promedio y conteo de reviews
        // Algoritmo: ordenar por rating promedio (peso 70%) y cantidad de reviews (peso 30%)
        $products = Product::query()
            ->where('stock', '>', 0) // Solo productos con stock
            ->withAvg('reviews', 'rating') // Calcular promedio de ratings
            ->withCount('reviews') // Contar número de reviews
            ->get()
            ->map(function ($product) {
                // Calcular puntuación compuesta
                $ratingScore = ($product->reviews_avg_rating ?? 0) * 0.7;
                $reviewsScore = min($product->reviews_count * 0.3, 30); // Max 30 puntos por reviews
                $product->score = $ratingScore + $reviewsScore;
                return $product;
            })
            ->sortByDesc('score') // Ordenar por puntuación
            ->take($limit); // Limitar resultados

        return response()->json([
            'data' => $products->values()->all()
        ]);
    }

    /**
     * Get related products for a specific product
     *
     * Returns products related to the given product based on:
     * - Same category
     * - Similar price range (±30%)
     * - Best ratings
     *
     * @urlParam id integer required The ID of the product. Example: 1
     * @queryParam limit integer Number of related products to return. Defaults to 4. Example: 6
     * 
     * @response [{
     *  "id": 5,
     *  "name": "RTX 4080",
     *  "category": "Targetes Gràfiques",
     *  "price": "1299.99",
     *  "image": "/images/rtx4080.jpg",
     *  "rating_avg": 4.7,
     *  "review_count": 18
     * }]
     */
    public function related($id, Request $request)
    {
        $limit = $request->input('limit', 4);

        // Obtener producto actual
        $product = Product::findOrFail($id);

        // Calcular rango de precios (±30%)
        $minPrice = $product->price * 0.7;
        $maxPrice = $product->price * 1.3;

        // Buscar productos relacionados
        $relatedProducts = Product::query()
            ->where('id', '!=', $id) // Excluir producto actual
            ->where('category', $product->category) // Misma categoría
            ->whereBetween('price', [$minPrice, $maxPrice]) // Rango de precio similar
            ->where('stock', '>', 0) // Solo con stock
            ->withAvg('reviews', 'rating') // Calcular rating promedio
            ->withCount('reviews') // Contar reviews
            ->get()
            ->sortByDesc('reviews_avg_rating') // Ordenar por mejor rating
            ->take($limit); // Limitar resultados

        return response()->json([
            'data' => $relatedProducts->values()->all()
        ]);
    }

    /**
     * Get sustainable products
     *
     * Returns products that meet sustainability criteria.
     * Filters products by eco_score, refurbished status, and other environmental factors.
     *
     * @queryParam min_eco_score integer Minimum eco score (0-100). Defaults to 70. Example: 80
     * @queryParam only_refurbished boolean Show only refurbished products. Example: true
     * @queryParam only_local boolean Show only local suppliers. Example: true
     * @queryParam page integer Page number. Example: 1
     * 
     * @response {
     *  "data": [{
     *    "id": 1,
     *    "name": "NVIDIA RTX 3080 Reacondicionada",
     *    "eco_score": 85,
     *    "is_refurbished": true,
     *    "has_eco_packaging": true,
     *    "carbon_footprint": "2.50",
     *    "eco_label": "🌿 Excelente"
     *  }],
     *  "stats": {
     *    "total_sustainable": 8,
     *    "avg_eco_score": 76.5,
     *    "total_co2_saved": "120.5 kg"
     *  }
     * }
     */
    public function sustainable(Request $request)
    {
        $minEcoScore = $request->input('min_eco_score', 70);
        
        $query = Product::query();

        // Filtro por eco_score mínimo
        $query->where('eco_score', '>=', $minEcoScore);

        // Filtro: solo reacondicionados
        if ($request->boolean('only_refurbished')) {
            $query->where('is_refurbished', true);
        }

        // Filtro: solo proveedores locales
        if ($request->boolean('only_local')) {
            $query->where('is_local_supplier', true);
        }

        // Obtener productos paginados
        $products = $query->paginate(12);

        // Calcular estadísticas de sostenibilidad
        $allSustainable = Product::where('eco_score', '>=', 70)->get();
        $stats = [
            'total_sustainable' => $allSustainable->count(),
            'avg_eco_score' => round($allSustainable->avg('eco_score'), 1),
            'total_refurbished' => Product::where('is_refurbished', true)->count(),
            'total_local' => Product::where('is_local_supplier', true)->count(),
            'total_co2_saved' => $allSustainable->where('carbon_footprint', '<', 5)->count() . ' productos',
        ];

        return response()->json([
            'data' => $products->items(),
            'stats' => $stats,
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * Create a new product
     *
     * @authenticated
     * 
     * @response {
     *  "id": 101,
     *  "name": "New Product",
     *  "price": 99.99,
     *  "stock": 10,
     *  "category": "Perifericos",
     *  "created_at": "...",
     *  "updated_at": "..."
     * }
     */
    public function store(Request $request)
    {
        // Verificar admin (redundante si se usa middleware, pero seguro)
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string',
            'image' => 'nullable|string', // Por ahora string, luego subida de archivos
            // Campos de sostenibilidad
            'eco_score' => 'nullable|integer|min:0|max:100',
            'is_refurbished' => 'boolean',
            'is_local_supplier' => 'boolean',
            'carbon_footprint' => 'nullable|numeric',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    /**
     * Update a product
     *
     * @authenticated
     * 
     * @urlParam product integer required The ID of the product.
     */
    public function update(Request $request, Product $product)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category' => 'sometimes|string',
            'image' => 'nullable|string',
            'eco_score' => 'nullable|integer|min:0|max:100',
            'is_refurbished' => 'boolean',
            'is_local_supplier' => 'boolean',
            'carbon_footprint' => 'nullable|numeric',
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    /**
     * Delete a product
     * 
     * @authenticated
     * 
     * @urlParam product integer required The ID of the product.
     */
    public function destroy(Request $request, Product $product)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * Get sustainability statistics

     *
     * Returns general statistics about sustainability in the product catalog.
     * 
     * @response {
     *  "total_products": 50,
     *  "sustainable_products": 12,
     *  "sustainability_percentage": 24,
     *  "avg_eco_score": 45.2,
     *  "refurbished_count": 5,
     *  "local_suppliers_count": 8,
     *  "avg_carbon_footprint": "4.5 kg CO2"
     * }
     */
    public function sustainabilityStats()
    {
        $totalProducts = Product::count();
        $sustainableProducts = Product::where('eco_score', '>=', 70)->count();
        $avgEcoScore = round(Product::avg('eco_score'), 1);
        $refurbishedCount = Product::where('is_refurbished', true)->count();
        $localCount = Product::where('is_local_supplier', true)->count();
        $avgCarbon = round(Product::whereNotNull('carbon_footprint')->avg('carbon_footprint'), 2);

        return response()->json([
            'total_products' => $totalProducts,
            'sustainable_products' => $sustainableProducts,
            'sustainability_percentage' => $totalProducts > 0 ? round(($sustainableProducts / $totalProducts) * 100) : 0,
            'avg_eco_score' => $avgEcoScore,
            'refurbished_count' => $refurbishedCount,
            'local_suppliers_count' => $localCount,
            'avg_carbon_footprint' => $avgCarbon . ' kg CO2',
        ]);
    }
}
