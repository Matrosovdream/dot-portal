<?php

namespace App\Actions\Api\V1\Subscriptions;

use App\Models\User;
use App\Models\UserSubscription;
use App\Services\Api\V1\Subscriptions\SubscriptionService;

class SubscriptionActions
{
    public function __construct(protected SubscriptionService $service) {}

    public function show(User $user): ?UserSubscription
    {
        return $this->service->currentForUser($user);
    }

    public function update(User $user, array $data): UserSubscription
    {
        return $this->service->changePlan($user, (int) $data['subscription_id'], [
            'drivers_number'  => $data['drivers_number']  ?? 0,
            'payment_card_id' => $data['payment_card_id'] ?? null,
        ]);
    }

    public function cancel(User $user): ?UserSubscription
    {
        return $this->service->cancel($user);
    }
}
