<?php

namespace App\Contracts\Payment;

interface PaymentInterface {

    public function chargeCustomerWithProfile(
        $user_id, 
        $amount, 
        $currency = 'USD', 
        $description = null
    ): array;

    public function chargeCustomerWithCard(
        $user_id, 
        $amount, 
        $currency = 'USD', 
        $description = null, 
        $cardDetails = []
    ): array;

    public function createSubscription(
        int $customerProfileId,
        int $paymentProfileId,
        int $price,
        string $title
    ): array;

    public function createSubscriptionWithUser(
        int $userId,
        int $price,
        string $title
    ): array;

}