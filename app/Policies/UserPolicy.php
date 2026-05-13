<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can browse users.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function browse(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can read the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $resource
     * @return boolean
     */
    public function read(User $user, User $resource)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $user->id == $resource->id;

        return $condition;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function add(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can edit the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $resource
     * @return boolean
     */
    public function edit(User $user, User $resource)
    {
        $condition = $user->id == $resource->id;
        $condition = $condition || $user->isAdmin() && ($user->id != $resource->id && !$resource->isAdmin());

        return $condition;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $resource
     * @return boolean
     */
    public function delete(User $user, User $resource)
    {
        $condition = $user->isAdmin();
        $condition = $condition && $user->id != $resource->id;
        $condition = $condition && !$resource->isAdmin();

        return $condition;
    }

    /**
     * Determine whether the user can use datatables for users.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function datatables(User $user)
    {
        return $user->isAdmin();
    }
}
