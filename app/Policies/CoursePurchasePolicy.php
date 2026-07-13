<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\CoursePurchase;

class CoursePurchasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can browse course subscriptions.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function browse(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can read the course subscription.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CoursePurchase  $coursePurchase
     * @return boolean
     */
    public function read(User $user, CoursePurchase $coursePurchase)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $user->id && $coursePurchase->user_id;

        return $condition;
    }

    /**
     * Determine whether the user can create course subscriptions.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function add(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can edit the course subscription.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CoursePurchase  $coursePurchase
     * @return boolean
     */
    public function edit(User $user, CoursePurchase $coursePurchase)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the course subscription.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CoursePurchase  $coursePurchase
     * @return boolean
     */
    public function delete(User $user, CoursePurchase $coursePurchase)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can use datatables for course subscriptions.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function datatables(User $user)
    {
        return $user->isAdmin();
    }
}
