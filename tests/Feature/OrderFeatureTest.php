<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Provider;
use App\Models\Inventory;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use App\Events\OrderPlaced;
use App\Mail\OrderConfirmationMail;

class OrderFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_happy_path_places_order_and_updates_inventory()
    {
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
        $provider = Provider::factory()->create();

        $response = $this->postJson('/api/provider/orders', [
            'provider_id' => $provider->id,
            // quantity missing
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('quantity');
    }
}
