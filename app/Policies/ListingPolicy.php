<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ListingPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Listing $listing): bool // add ? before User
    {
        return $listing->user->role !== 'suspended' && $listing->approved;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role !== 'suspended';
    }

    /**
     * Determine whether the user can modify a record
     */
    public function modify(User $user, Listing $listing): bool
    {
        // if user is not suspended and the user is same as the owner of the listing user id === listing user_id
        return $user->role !== 'suspended' && $user->id === $listing->user_id;
    }
}
