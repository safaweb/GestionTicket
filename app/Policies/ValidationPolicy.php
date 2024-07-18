<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Validation;
use App\Models\User;

class ValidationPolicy
{
    /** * Determine whether the user can view any models. */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any Validation');
    }

    /** * Determine whether the user can view the model. */
    public function view(User $user, Validation $validation): bool
    {
        return $user->can('view Validation');
    }
}
