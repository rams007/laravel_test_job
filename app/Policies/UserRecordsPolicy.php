<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\UserRecord;
use App\Models\User;

class UserRecordsPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\UserRecord $userRecord
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, UserRecord $userRecord)
    {
        $allow = false;
        if ($user->id === $userRecord->user_id) {
            $allow = true;
        }

        if ($user->role == 'manager') {
            $allow = true;
        }
        return $allow;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\UserRecord $userRecord
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, UserRecord $userRecord)
    {
        $allow = false;
        if ($user->id === $userRecord->user_id) {
            $allow = true;
        }

        if ($user->role == 'manager') {
            //only manager assigned to this employee can delete records
            $employeeUserRecord = User::find($userRecord->user_id);
            if ($employeeUserRecord) {
                if ($employeeUserRecord->manager_id == $user->id) {
                    $allow = true;
                }
            }
        }
        return $allow;
    }

}
