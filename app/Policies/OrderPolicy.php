<?php

namespace App\Policies;

use App\Models\User;

class OrderPolicy
{
    /**
     * Create a new policy instance.
     */
     public function place(?User $user, $providerId): bool
    {
        // Example: if you had auth and mapping it would be:
        // return $user && $user->provider_id == $providerId;
        return true;
    }
}
