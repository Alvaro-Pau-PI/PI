<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

/**
 * @group Reviews
 *
 * APIs for managing product reviews
 */
class ReviewController extends Controller
{
    /**
     * Get reviews for a product
     *
     * Returns a list of reviews for a specific product.
     *
     * @urlParam product integer required The ID of the product. Example: 1
     */
    public function index($productId)
    {
        $reviews = Review::where('product_id', $productId)->with('user')->get();
        return response()->json($reviews);
    }

    /**
     * Create a review
     *
     * Store a new review for a product. User must be authenticated.
     *
     * @authenticated
     * @urlParam product integer required The ID of the product. Example: 1
     * @bodyParam text string required The content of the review. Example: "Great product!"
     * @bodyParam rating integer required The rating from 1 to 5. Example: 5
     * @response 201 {
     *  "id": 1,
     *  "user_id": 1,
     *  "product_id": 1,
     *  "text": "Great product!",
     *  "rating": 5,
     *  "created_at": "...",
     *  "updated_at": "..."
     * }
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'text' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        // Comprovar si l'usuari ja ha valorat aquest producte
        $existingReview = Review::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'Ja has valorat aquest producte. Només es permet una valoració per producte.'
            ], 409);
        }

        $review = Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $productId,
            'text' => $request->text,
            'rating' => $request->rating,
        ]);

        return response()->json($review->load('user'), 201);
    }
}
