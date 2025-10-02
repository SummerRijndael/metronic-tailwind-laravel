<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // Can view another user's profile
    public function view(User $user, User $profileUser): bool
    {
        return $user->id === $profileUser->id || $user->hasPermission('view_profile');
    }

    // Can update another user's profile
    public function update(User $user, User $profileUser): bool
    {
        return $user->id === $profileUser->id || $user->hasPermission('edit_profile');
    }

    // Can delete another user
    public function delete(User $user, User $profileUser): bool
    {
        return $user->hasPermission('delete_user');
    }
}
