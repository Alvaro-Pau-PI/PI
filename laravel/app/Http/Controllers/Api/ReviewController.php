<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

/**
 * @group Reviews
 *
 * APIs para gestionar las reseñas de productos.
 */
class ReviewController extends Controller
{
    /**
     * Listar reseñas de un producto
     *
     * Devuelve la lista de reseñas de un producto específico.
     *
     * @urlParam product integer required El ID del producto. Example: 1
     */
    public function index($productId)
    {
        $reviews = Review::where('product_id', $productId)->with('user')->get();
        return response()->json($reviews);
    }

    /**
     * Crear una reseña
     *
     * Almacena una nueva reseña para un producto. El usuario debe estar autenticado.
     *
     * @authenticated
     * @urlParam product integer required El ID del producto. Example: 1
     * @bodyParam text string required El contenido de la reseña. Example: "¡Gran producto!"
     * @bodyParam rating integer required La puntuación de 1 a 5. Example: 5
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'text' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        // Comprobar si el usuario ya ha valorado este producto
        $existingReview = Review::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'Ya has valorado este producto. Solo se permite una valoración por producto.'
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

    /**
     * Actualizar una reseña
     *
     * Permite al propietario de la reseña actualizar su texto y puntuación.
     *
     * @authenticated
     * @urlParam review integer required El ID de la reseña. Example: 1
     * @bodyParam text string required El nuevo contenido de la reseña. Example: "Actualizado: sigue siendo genial"
     * @bodyParam rating integer required La nueva puntuación de 1 a 5. Example: 4
     */
    public function update(Request $request, Review $review)
    {
        // Solo el propietario puede editar su reseña
        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'No tienes permiso para editar esta reseña.'
            ], 403);
        }

        $request->validate([
            'text' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $review->update([
            'text' => $request->text,
            'rating' => $request->rating,
        ]);

        return response()->json($review->load('user'));
    }

    /**
     * Eliminar una reseña
     *
     * Permite al propietario eliminar su reseña, o a un administrador eliminar cualquier reseña.
     *
     * @authenticated
     * @urlParam review integer required El ID de la reseña. Example: 1
     */
    public function destroy(Request $request, Review $review)
    {
        $user = $request->user();

        // El propietario puede eliminar su propia reseña, o un admin puede eliminar cualquiera
        if ($review->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json([
                'message' => 'No tienes permiso para eliminar esta reseña.'
            ], 403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Reseña eliminada correctamente.'
        ]);
    }
}
