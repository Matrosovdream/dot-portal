<?php

namespace App\Services\Api\V1\Subscriptions;

use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use App\Repositories\Subscription\SubscriptionRepo;
use App\Repositories\User\UserSubscriptionRepo;

/**
 * Lifecycle of a user's subscription: change plan, cancel.
 * The cancel flow refuses anything that's not active/trial — preventing
 * the auto-created "disabled" placeholder (UserObserver) from being cancelled.
 */
class SubscriptionService
{
    public function __construct(
        protected SubscriptionRepo     $planRepo,
        protected UserSubscriptionRepo $userSubRepo,
    ) {}

    public function currentForUser(User $user): ?UserSubscription
    {
        return $this->userSubRepo->getModel()::where('user_id', $user->id)
            ->with('subscription')
            ->latest()
            ->first();
    }

    public function changePlan(User $user, int $planId, array $opts = []): UserSubscription
    {
        /** @var Subscription $plan */
        $plan = $this->planRepo->getModel()::findOrFail($planId);

        return $this->userSubRepo->getModel()::updateOrCreate(
            ['user_id' => $user->id],
            [
                'subscription_id'  => $plan->id,
                'price_per_driver' => $plan->price_per_driver ?? 0,
                'drivers_number'   => $opts['drivers_number'] ?? 0,
                'discount'         => $plan->discount ?? 0,
                'payment_card_id'  => $opts['payment_card_id'] ?? null,
                'start_date'       => now(),
                'status'           => 'active',
            ],
        )->fresh('subscription');
    }

    public function cancel(User $user): ?UserSubscription
    {
        $sub = $this->userSubRepo->getModel()::where('user_id', $user->id)
            ->whereIn('status', ['active', 'trial'])
            ->first();

        if (!$sub) return null;

        $sub->update(['status' => 'cancelled', 'end_date' => now()]);
        return $sub->fresh('subscription');
    }
}
