<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(OrderStoreRequest $request)
    {
        $data = $request->validated();
        // Basic authorization via policy (provider ownership / roles would be here)
        $this->authorize('place', [\App\Models\Order::class, $data['provider_id']]);

       $order = $this->orderService->placeOrder($data['provider_id'], $data['quantity']);

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order' => $order,
            'updated_stock' => $order->provider->inventory->stock,
            'email_preview' => (new \App\Mail\OrderConfirmationMail($order))->render(),

        ], 201);
    }
}
