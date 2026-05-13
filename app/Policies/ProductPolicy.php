<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can browse products.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function browse(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can read the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return boolean
     */
    public function read(User $user, Product $product)
    {
        return true;
    }

    /**
     * Determine whether the user can create products.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function add(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can edit the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return boolean
     */
    public function edit(User $user, Product $product)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $user->id == $product->user_id;

        return $condition;
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return boolean
     */
    public function delete(User $user, Product $product)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $user->id == $product->user_id;

        return $condition;
    }

    /**
     * Determine whether the user can use datatables for products.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function datatables(User $user)
    {
        return $user->isAdmin();
    }
}
