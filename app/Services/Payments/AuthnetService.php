<?php

namespace App\Services\Payments;

use App\Contracts\Payment\PaymentInterface;
use App\Mixins\Gateways\AuthnetGateway;

class AuthnetService implements PaymentInterface
{
    private $processor;
    private $cardService;

    public function __construct() {

        $this->processor = new AuthnetGateway();
        $this->cardService = new PaymentCardService();

    }

    public function chargeCustomerWithProfile($user_id, $total, $currency="USD", $description=null): array {

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
                'transactionId' => $paymentRes['transactionId'] ?? null,
            ];

        }

        $res['card'] = $card;

        return $res;

    }

    public function chargeCustomerWithCard($user_id, $amount, $currency = 'USD', $description = null, $cardDetails = []): array
    {

        $user = auth()->user();

        if (!$user) {
            return ['error' => 'User not authenticated.'];
        }

        // Validate card details
        if (empty($cardDetails) || !isset($cardDetails['card_number'])) {
            return ['error' => 'Invalid card details provided.'];
        }

        $cardDetails['email'] = $user->email;
        
        $paymentRes = $this->processor->chargeCustomerCard(
            $cardDetails,
            $amount,
            $description
        );

        if (!$paymentRes['success']) {
            return [
                'success' => false,
                'message' => $paymentRes['message'] ?? 'Payment processing failed.',
            ];
        }

        return [
            'success' => true,
            'message' => $paymentRes['message'] ?? 'Payment processed successfully.',
            'transactionId' => $paymentRes['transactionId'] ?? null,
        ];

    }

    public function createSubscription(
        int $customerProfileId, 
        int $paymentProfileId, 
        int $price, 
        string $title): array 
    {

        // Subscribe user
        return $this->processor->createSubscription(
            $customerProfileId,
            $paymentProfileId,
            $price,
        );
        
    }

    public function createSubscriptionWithUser(
        int $userId, 
        int $price, 
        string $title): array 
    {

        // Get user profile
        $profile = $this->cardService->getUserPrimaryCard($userId);

        if (!$profile) {
            return ['error' => 'No payment profile found for user.'];
        }

        // Create subscription
        return $this->createSubscription(
            $profile['customerProfileId'],
            $profile['paymentProfileId'],
            $price,
            $title='Subscription for ' . $title
        );

    }

}