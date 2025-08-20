<?php

namespace App\Observers;

use App\Models\User;

use App\Repositories\User\UserSubscriptionRepo;

class UserObserver
{

    public function __construct(
        protected UserSubscriptionRepo $userSubRepo
    )
    {

    }

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

    public function updated(User $user): void
    {

    }

    public function deleted(User $user): void
    {
        //
    }

    public function restored(User $user): void
    {
        //
    }

    public function forceDeleted(User $user): void
    {
        //
    }
}
