<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Mostrar listado del recurso.
     */
    public function index($productId)
    {
        $reviews = Review::where('product_id', $productId)->with('user')->get();
        return response()->json($reviews);
    }

    /**
     * Almacenar un recurso recién creado en almacenamiento.
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'text' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $review = Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $productId,
            'text' => $request->text,
            'rating' => $request->rating,
        ]);

        return response()->json($review->load('user'), 201);
    }
}
