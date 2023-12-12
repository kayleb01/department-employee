<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
{
   
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Department $department): Response
    {
        return $department->user_id === $user->id
            ? Response::allow()
            : Response::deny('You cannot update this department.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Department $department): Response
    {
        return $department->user_id === $user->id
            ? Response::allow()
            : Response::deny('You cannot delete this department');
        
    }
}
