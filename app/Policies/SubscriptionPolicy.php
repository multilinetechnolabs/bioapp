<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Subscription;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can browse subscriptions.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function browse(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can read the subscription.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Subscription  $subscription
     * @return boolean
     */
    public function read(User $user, Subscription $subscription)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $user->id && $subscription->user_id;
        
        return $condition;
    }

    /**
     * Determine whether the user can create subscriptions.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function add(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can edit the subscription.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Subscription  $subscription
     * @return boolean
     */
    public function edit(User $user, Subscription $subscription)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the subscription.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Subscription  $subscription
     * @return boolean
     */
    public function delete(User $user, Subscription $subscription)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can use datatables for subscriptions.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function datatables(User $user)
    {
        return $user->isAdmin();
    }
}
