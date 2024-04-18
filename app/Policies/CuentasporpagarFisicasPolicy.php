<?php

namespace App\Policies;

use App\Models\User;
use App\Models\cuentasporpagar_fisicas;
use Illuminate\Auth\Access\HandlesAuthorization;

class CuentasporpagarFisicasPolicy
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
     * @param  \App\Models\cuentasporpagar_fisicas  $cuentasporpagarFisicas
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, cuentasporpagar_fisicas $cuentasporpagarFisicas)
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
     * @param  \App\Models\cuentasporpagar_fisicas  $cuentasporpagarFisicas
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, cuentasporpagar_fisicas $cuentasporpagarFisicas)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\cuentasporpagar_fisicas  $cuentasporpagarFisicas
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, cuentasporpagar_fisicas $cuentasporpagarFisicas)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\cuentasporpagar_fisicas  $cuentasporpagarFisicas
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, cuentasporpagar_fisicas $cuentasporpagarFisicas)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\cuentasporpagar_fisicas  $cuentasporpagarFisicas
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, cuentasporpagar_fisicas $cuentasporpagarFisicas)
    {
        //
    }
}
