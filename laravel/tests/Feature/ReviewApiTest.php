<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;

class ReviewApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting reviews for a product.
     */
    public function test_can_list_reviews_for_product()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();
        
        Review::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'text' => 'Great product!',
            'rating' => 5
        ]);

        $response = $this->getJson("/api/products/{$product->id}/reviews");

        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['text' => 'Great product!']);
    }

    /**
     * Test creating a review requires authentication.
     */
    public function test_cannot_create_review_if_unauthenticated()
    {
        $product = Product::factory()->create();

        $response = $this->postJson("/api/products/{$product->id}/reviews", [
            'text' => 'My review',
            'rating' => 4
        ]);

        $response->assertStatus(401); // Unauthorized
    }

    /**
     * Test authenticated user can create a review.
     */
    public function test_authenticated_user_can_create_review()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/products/{$product->id}/reviews", [
            'text' => 'Amazing!',
            'rating' => 5
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('reviews', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'text' => 'Amazing!',
            'rating' => 5
        ]);
    }

    /**
     * Test validation: text is required.
     */
    public function test_review_requires_text()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/products/{$product->id}/reviews", [
            'rating' => 5
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['text']);
    }

    /**
     * Test validation: rating must be valid.
     */
    public function test_review_rating_must_be_valid()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/products/{$product->id}/reviews", [
            'text' => 'Bad rating',
            'rating' => 6 // Invalid
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['rating']);
    }
}
