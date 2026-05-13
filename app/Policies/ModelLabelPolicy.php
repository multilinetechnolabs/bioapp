<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\ModelLabel;

class ModelLabelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can browse modelLabels.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function browse(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can read the modelLabel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModelLabel  $modelLabel
     * @return boolean
     */
    public function read(User $user, ModelLabel $modelLabel)
    {
        return true;
    }

    /**
     * Determine whether the user can create modelLabels.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function add(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can edit the modelLabel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModelLabel  $modelLabel
     * @return boolean
     */
    public function edit(User $user, ModelLabel $modelLabel)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the modelLabel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModelLabel  $modelLabel
     * @return boolean
     */
    public function delete(User $user, ModelLabel $modelLabel)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can use datatables for modelLabels.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function datatables(User $user)
    {
        return $user->isAdmin();
    }
}
