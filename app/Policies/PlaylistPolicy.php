<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Playlist;

class PlaylistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can browse playlists.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function browse(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can read the playlist.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Playlist  $playlist
     * @return boolean
     */
    public function read(User $user, Playlist $playlist)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $playlist->user_id == $user->id;

        return $condition;
    }

    /**
     * Determine whether the user can create playlists.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function add(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can edit the playlist.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Playlist  $playlist
     * @return boolean
     */
    public function edit(User $user, Playlist $playlist)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $playlist->user_id == $user->id;

        return $condition;
    }

    /**
     * Determine whether the user can delete the playlist.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Playlist  $playlist
     * @return boolean
     */
    public function delete(User $user, Playlist $playlist)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $playlist->user_id == $user->id;

        return $condition;
    }
}
