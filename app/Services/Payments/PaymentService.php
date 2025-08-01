<?php

namespace App\Services\Payments;

use App\Mixins\Gateways\AuthnetGateway;

class PaymentService {

    private $processor;
    private $cardService;

    public function __construct() {

        $this->processor = new AuthnetGateway();
        $this->cardService = new PaymentCardService();

    }

    public function chargeCustomerWithProfile($user_id, $total): array {

        // Get the user's primary payment card
        $card = $this->cardService->getUserPrimaryCard($user_id);

        if (!$card) {
            return ['error' => 'No primary payment card found for user.'];
        }

        $paymentRes = $this->processor->chargeCustomerProfile(
            $card['customerProfileId'],
            $card['paymentProfileId'],
            $total,
        );

        if (!$paymentRes['success']) {
            $res = [
                'success' => false,
                'message' => $paymentRes['message'] ?? 'Payment processing failed.',
            ];
        } else {

            $res = [
                'success' => true,
                'message' => $paymentRes['message'] ?? 'Payment processing failed.',
                'transaction_id' => $paymentRes['transactionId'] ?? null,
            ];

        }

        $res['card'] = $card;

        return $res;

    }


}