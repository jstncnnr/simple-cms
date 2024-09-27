<?php

namespace App\Policies;

use App\Models\User;
use App\Permissions\UserPermissions;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(UserPermissions::ViewAll->getKey());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->can(UserPermissions::ViewAll->getKey());
    }

    /**
     * Determine whether the user can create models.
     * We don't allow direct creation of users. Only
     * invites.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can invite models.
     */
    public function invite(User $user): bool
    {
        return $user->can(UserPermissions::Invite->getKey());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->can(UserPermissions::Update->getKey());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Do not allow users to delete themselves
        if($user->id === $model->id)
            return false;

        return $user->can(UserPermissions::Delete->getKey());
    }

    /**
     * Determine whether the user can restore the model.
     * We do not use soft deletes, so prevent it just in case
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * We do not use soft deletes, so prevent it just in case
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
