<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Payment;

class PaymentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can browse payments.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function browse(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can read the payment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payment  $payment
     * @return boolean
     */
    public function read(User $user, Payment $payment)
    {
        $condition = $user->isAdmin();
        $condition = $condition || $user->id == $payment->user_id;

        return $condition;
    }

    /**
     * Determine whether the user can create payments.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function add(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can edit the payment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payment  $payment
     * @return boolean
     */
    public function edit(User $user, Payment $payment)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the payment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payment  $payment
     * @return boolean
     */
    public function delete(User $user, Payment $payment)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can use datatables for payments.
     *
     * @param  \App\Models\User  $user
     * @return boolean
     */
    public function datatables(User $user)
    {
        return $user->isAdmin();
    }
}
