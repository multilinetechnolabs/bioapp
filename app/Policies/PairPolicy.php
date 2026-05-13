<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Pair;

class PairPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can browse pairs.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function browse(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can read the pair.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pair  $pair
     * @return boolean
     */
    public function read(User $user, Pair $pair)
    {
        return true;
    }

    /**
     * Determine whether the user can create pairs.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function add(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can edit the pair.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pair  $pair
     * @return boolean
     */
    public function edit(User $user, Pair $pair)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the pair.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pair  $pair
     * @return boolean
     */
    public function delete(User $user, Pair $pair)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can use datatables for pairs.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function datatables(User $user)
    {
        return $user->isAdmin();
    }
}
