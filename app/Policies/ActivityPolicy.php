<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Activity;

class ActivityPolicy
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
     * @param  \App\Models\Activity  $activity
     * @return boolean
     */
    public function read(User $user, Activity $activity)
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
        return true;
    }

    /**
     * Determine whether the user can edit the activity.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Activity  $activity
     * @return boolean
     */
    public function edit(User $user, Activity $activity)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $user->id == $activity->user_id;

        return $condition;
    }

    /**
     * Determine whether the user can delete the activity.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Activity  $activity
     * @return boolean
     */
    public function delete(User $user, Activity $activity)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $user->id == $activity->user_id;

        return $condition;
    }
}
