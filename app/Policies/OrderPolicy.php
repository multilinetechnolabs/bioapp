<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can browse orders.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function browse(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can read the order.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return boolean
     */
    public function read(User $user, Order $order)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $user->id == $order->user_id;

        return $condition;
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function add(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can edit the order.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return boolean
     */
    public function edit(User $user, Order $order)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return boolean
     */
    public function delete(User $user, Order $order)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can use datatables for orders.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function datatables(User $user)
    {
        return $user->isAdmin();
    }
}
