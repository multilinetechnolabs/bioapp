<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Activity\Category as ActivityCategory;

class ActivityCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can browse activities.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function browse(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can read the activity.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Activity\Category  $activity
     * @return boolean
     */
    public function read(User $user, ActivityCategory $activity)
    {
        return true;
    }

    /**
     * Determine whether the user can create activities.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function add(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can edit the activity.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Activity\Category  $activity
     * @return boolean
     */
    public function edit(User $user, ActivityCategory $activity)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the activity.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Activity\Category  $activity
     * @return boolean
     */
    public function delete(User $user, ActivityCategory $activity)
    {
        return $user->isAdmin();
    }
}
