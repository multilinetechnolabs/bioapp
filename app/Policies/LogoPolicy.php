<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Logo;

class LogoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can browse logos.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function browse(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can read the logo.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Logo  $logo
     * @return boolean
     */
    public function read(User $user, Logo $logo)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $logo->user_id == $user->id;

        return $condition;
    }

    /**
     * Determine whether the user can create logos.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function add(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can edit the logo.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Logo  $logo
     * @return boolean
     */
    public function edit(User $user, Logo $logo)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $logo->user_id == $user->id;

        return $condition;
    }

    /**
     * Determine whether the user can delete the logo.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Logo  $logo
     * @return boolean
     */
    public function delete(User $user, Logo $logo)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $logo->user_id == $user->id;

        return $condition;
    }
}
