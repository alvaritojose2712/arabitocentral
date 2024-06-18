<?php

namespace App\Policies;

use App\Models\User;
use App\Models\productonombre5s;
use Illuminate\Auth\Access\HandlesAuthorization;

class Productonombre5sPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\productonombre5s  $productonombre5s
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, productonombre5s $productonombre5s)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\productonombre5s  $productonombre5s
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, productonombre5s $productonombre5s)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\productonombre5s  $productonombre5s
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, productonombre5s $productonombre5s)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\productonombre5s  $productonombre5s
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, productonombre5s $productonombre5s)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\productonombre5s  $productonombre5s
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, productonombre5s $productonombre5s)
    {
        //
    }
}
