<?php

namespace App\Policies;

use App\Models\Tutorial;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TutorialPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Any authenticated user can view tutorials
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tutorial $tutorial): bool
    {
        // Any authenticated user can view a tutorial
        // If the tutorial is premium, only paid users or the creator can view it
        if ($tutorial->is_premium) {
            return $user->id === $tutorial->user_id || $user->isAdmin() || $user->subscription_status === 'active';
        }
        
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Admin, Mentor, and Estudiante roles can create tutorials
        return $user->isAdmin() || $user->isMentor() || $user->isEstudiante();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tutorial $tutorial): bool
    {
        // Only the creator or an admin can update the tutorial
        return $user->id === $tutorial->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tutorial $tutorial): bool
    {
        // Only the creator or an admin can delete the tutorial
        return $user->id === $tutorial->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tutorial $tutorial): bool
    {
        // Only the creator or an admin can restore the tutorial
        return $user->id === $tutorial->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tutorial $tutorial): bool
    {
        // Only an admin can permanently delete a tutorial
        return $user->isAdmin();
    }
}
