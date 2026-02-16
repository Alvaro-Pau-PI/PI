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
}
