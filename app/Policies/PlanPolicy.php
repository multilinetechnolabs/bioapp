<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Plan;

class PlanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can browse plans.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function browse(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can read the plan.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Plan  $plan
     * @return boolean
     */
    public function read(User $user, Plan $plan)
    {
        return true;
    }

    /**
     * Determine whether the user can create plans.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function add(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can edit the plan.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Plan  $plan
     * @return boolean
     */
    public function edit(User $user, Plan $plan)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the plan.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Plan  $plan
     * @return boolean
     */
    public function delete(User $user, Plan $plan)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can use datatables for plans.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function datatables(User $user)
    {
        return $user->isAdmin();
    }
}
