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
}
