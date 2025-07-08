<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('access_user');
    }

    public function view(User $user, User $selectUser): bool
    {
        return $user->can('access_user');
    }

    public function create(User $user): bool
    {
        return $user->can('create_user');
    }

    public function update(User $user, User $selectUser): bool
    {
        return $user->can('update_user');
    }

    public function delete(User $user,  User $selectUser): bool
    {
        return $user->can('delete_user');
    }
}
