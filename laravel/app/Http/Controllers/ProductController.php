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
    public function apiIndex()
    {
        return response()->json(Product::all());
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
