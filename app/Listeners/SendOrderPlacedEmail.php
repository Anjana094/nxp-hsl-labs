<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\OrderConfirmationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderPlacedEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event)
    {
        $order = $event->order;
        $provider = $order->provider;

        // Generate mailable content but do NOT send email
        $mailable = new OrderConfirmationMail($event->order);

        // Render mailable HTML as a string
        $htmlContent = $mailable->render();

        // Log for testing instead of sending
        Log::info('📩 Order Confirmation Email Preview:');
        Log::info($htmlContent);
        // Mail is queued — local dev can use sync queue if you prefer
        //Mail::to($provider->email)->queue(new OrderConfirmationMail($order));
    }
}
