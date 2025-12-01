<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    public function access(User $user, Order $order): Response
    {
        return $order->isOwnedBy($user->id)
            ? Response::allow()
            : Response::denyWithStatus(401, 'You do not own this order.');
    }
}
