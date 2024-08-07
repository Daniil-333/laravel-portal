<?php

namespace App\Policies;

use App\Models\Receipt;
use App\Models\User;
use Illuminate\Support\Facades\Config;

class ReceiptPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Receipt $receipt): bool
    {
        return $user->role == Config::get('constants.role.EDITOR');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role == Config::get('constants.role.MODERATOR');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Receipt $receipt): bool
    {
        return $user->role == Config::get('constants.role.MODERATOR') || $user->role == Config::get('constants.role.EDITOR');
    }

    /**
     * Страница редактирования Рецепта
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->role == Config::get('constants.role.MODERATOR') || $user->role == Config::get('constants.role.EDITOR');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Receipt $receipt): bool
    {
        return $user->role == Config::get('constants.role.MODERATOR');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Receipt $receipt): bool
    {
        return $user->role == Config::get('constants.role.MODERATOR');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Receipt $receipt): bool
    {
        return $user->role == Config::get('constants.role.MODERATOR');
    }
}
