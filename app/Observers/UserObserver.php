<?php

namespace App\Observers;

use App\Models\User;

use App\Repositories\User\UserSubscriptionRepo;

class UserObserver
{

    protected $userSubRepo;

    public function __construct()
    {
        $this->userSubRepo = new UserSubscriptionRepo();
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {

        // Create user subscription with null values
        $sub = $this->userSubRepo->create(
            [
                'user_id' => $user->id,
                'status' => 'disabled',
            ]
        );

    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
