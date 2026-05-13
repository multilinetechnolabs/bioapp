<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Media;

class MediaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can browse medias.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function browse(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can read the media.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Media  $media
     * @return boolean
     */
    public function read(User $user, Media $media)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $media->user_id == $user->id;

        return $condition;
    }

    /**
     * Determine whether the user can create medias.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function add(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can edit the media.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Media  $media
     * @return boolean
     */
    public function edit(User $user, Media $media)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $media->user_id == $user->id;

        return $condition;
    }

    /**
     * Determine whether the user can delete the media.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Media  $media
     * @return boolean
     */
    public function delete(User $user, Media $media)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $media->user_id == $user->id;

        return $condition;
    }
}
