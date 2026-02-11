<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
     * Mostrar listado del recurso (API).
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
     * Mostrar el recurso especificado (API).
     */
    public function apiShow(Product $product)
    {
        return response()->json($product);
    }
}
