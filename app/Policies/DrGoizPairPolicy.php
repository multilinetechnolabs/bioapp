<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\DrGoizPair;

class DrGoizPairPolicy
{
    use HandlesAuthorization;

    public function browse(User $user)
    {
        return true;
    }

    public function read(User $user, DrGoizPair $pair)
    {
        return true;
    }

    public function add(User $user)
    {
        return $user->isAdmin();
    }

    public function edit(User $user, DrGoizPair $pair)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, DrGoizPair $pair)
    {
        return $user->isAdmin();
    }

    public function datatables(User $user)
    {
        return $user->isAdmin();
    }
}
