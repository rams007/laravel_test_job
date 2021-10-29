<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class UserPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role === 'manager';
    }

    /**
     * Determine whether the user can delete the model. We can delete only our users
     *
     * @param \App\Models\User $user
     * @param \App\Models\User $userRecord
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $userRecord)
    {
        $allow = false;
        if ($user->role == 'manager' AND $user->id == $userRecord->manager_id) {
            $allow = true;
        }
        return $allow;
    }

}
