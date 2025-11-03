<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Models\Inventory;
use App\Models\Provider;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderService
{
    public function placeOrder($providerId, $quantity): Order
    {
        return DB::transaction(function () use ($providerId, $quantity) {
            // Create order
            $order = Order::create([
                'provider_id' => $providerId,
                'quantity' => $quantity,
                'status' => 'placed',
                'ordered_at' => now(),
            ]);

            // Update inventory
            $inventory = Inventory::firstOrCreate(
                ['provider_id' => $providerId],
                ['stock' => 0]
            );

            $inventory->stock += $quantity;
            $inventory->save();

            // Reload relationships for response
            $order->load('provider.inventory');

            // domain event
            event(new OrderPlaced($order));

            return $order;
        });
    }
}
