<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Provider;
use App\Models\Inventory;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use App\Events\OrderPlaced;
use App\Mail\OrderConfirmationMail;
use Laravel\Sanctum\Sanctum;

class OrderFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_happy_path_places_order_and_updates_inventory()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Mail::fake();
        Event::fakeExcept(\App\Events\OrderPlaced::class);

        $provider = Provider::factory()->create();
        $provider->inventory()->create(['stock' => 10]);

        $response = $this->postJson('api/provider/orders', [
            'provider_id' => $provider->id,
            'quantity' => 5,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'provider_id' => $provider->id,
            'quantity' => 5,
        ]);

        // Inventory increased
        $this->assertEquals(15, $provider->inventory->fresh()->stock);

        // Email queued
        // Mail::assertQueued(OrderConfirmationMail::class);
    }

    public function test_validation_fails_when_quantity_missing()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $provider = Provider::factory()->create();

        $response = $this->postJson('/api/provider/orders', [
            'provider_id' => $provider->id,
            // quantity missing
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('quantity');
    }

    /** Unauthorized user should NOT access order API */
    public function test_unauthorized_user_cannot_place_order()
    {
        $response = $this->postJson('/api/provider/orders', [
            'provider_id' => 1,
            'quantity' => 5,
        ]);

        $response->assertStatus(401); // return unauthorized
    }

    /** Order API fails for invalid provider or quantity */
    public function test_order_fails_with_invalid_provider_or_quantity()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Case 1: Invalid provider_id (provider does not exist)
        $response = $this->postJson('/api/provider/orders', [
            'provider_id' => 999,   // non-existing provider
            'quantity' => 5,
        ]);

        $response->assertStatus(422); // Validation fails

        // Case 2: Missing quantity
        $provider = Provider::factory()->create();
        $response = $this->postJson('/api/provider/orders', [
            'provider_id' => $provider->id,
            //quantity missing
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['quantity']);
    }
}
