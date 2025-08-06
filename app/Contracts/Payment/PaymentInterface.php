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

}