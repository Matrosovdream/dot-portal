<?php
namespace App\Actions\Dashboard;

use App\Repositories\Subscription\SubscriptionRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\User\UserSubscriptionRepo;
use App\Repositories\User\UserPaymentHistoryRepo;
use App\Repositories\User\UserPaymentCardRepo;
use App\Mixins\Gateways\AuthnetGateway;
use Carbon\Carbon;

/*
        Test cards
        4111111111111111 - Visa
        5500000000000004 - MasterCard
        340000000000009 - American Express
        6011000000000004 - Discover
        3000000000000009 - Diners Club
        */

class SubscriptionUserActions
{

    private $userRepo;
    private $userSubRepo;
    private $subRepo;
    private $userPaymentHistoryRepo;
    private $userCardRepo;
    private $authnet;

    public function __construct()
    {
        $this->userRepo = new UserRepo();
        $this->userSubRepo = new UserSubscriptionRepo();
        $this->subRepo = new SubscriptionRepo();
        $this->userPaymentHistoryRepo = new UserPaymentHistoryRepo();
        $this->userCardRepo = new UserPaymentCardRepo();
        $this->authnet = new AuthnetGateway;
    }

    public function index()
    {

        $user_id = auth()->user()->id;

        $subscription = $this->userSubRepo->getByUserID($user_id);

        $data = [
            'title' => 'My subscription',
            'user' => $this->userRepo->getByID($user_id),
            'subscription' => $subscription,
            'allSubscriptions' => $this->subRepo->getAll([], 100),
            'paymentHistory' => $this->userPaymentHistoryRepo->getAll(['user_id' => $user_id], 100, ['payment_date' => 'desc']),
        ];

        if ($subscription['subscription']) {

            // Calculate percent of used drivers
            $data['subscription']['driversUsedPercent'] = round($data['subscription']['driversUsed'] / $data['subscription']['subscription']['drivers_amount'] * 100, 2);

        }

        // Test
        if (request('test')) {
            $this->testCard();
        }

        // See all subscriptions
        if (request('all_subs')) {
            $this->allSubscriptions();
        }

        // Cancel all subscriptions
        if (request('cancel_subs')) {
            $this->cancelSubscriptions();
        }

        return $data;
    }

    public function updateSubscription($request)
    {

        $user_id = auth()->user()->id;

        // Get subscription by request
        $subscriptionPlan = $this->subRepo->getByID($request['plan']);
        $userSubscription = $this->userSubRepo->getByUserID($user_id);
        if (!$userSubscription) {
            return false;
        }

        // Let's cancel the old subscription
        $authnetSubId = $userSubscription['Model']->getMeta('authnet_sub_id');
        if ($authnetSubId) {
            $cancelSub = $this->authnet->cancelSubscription($authnetSubId);

            // Check if cancel was successful
            if (!$cancelSub['success']) {
                return [
                    'success' => false,
                    'message' => $cancelSub['message'],
                ];
            }
        }

        // Get user primary card
        $primaryCard = $this->userCardRepo->getUserPrimaryCard($user_id);
        $profile = [
            'customerProfileId' => $primaryCard['Meta']['authnet_profile_id'],
            'paymentProfileId' => $primaryCard['Meta']['authnet_payment_profile_id'],
        ];

        // Charge the first payment
        $firstPayment = $this->authnet->chargeCustomerProfile(
            $profile['customerProfileId'],
            $profile['paymentProfileId'],
            $subscriptionPlan['price'],
        );

        if ($firstPayment['success']) {
            // Create payment history record
            $this->userPaymentHistoryRepo->create([
                'user_id' => $user_id,
                'payment_method_id' => 1,
                'subscription_id' => $userSubscription['id'],
                'type' => 'subscription',
                'amount' => $subscriptionPlan['price'],
                'payment_date' => date('Y-m-d H:i:s'),
                'transaction_id' => $firstPayment['transactionId'],
                'status' => 'success',
                'notes' => 'First payment for subscription "' . $subscriptionPlan['name'] . '"',
            ]);
        } else {
            return [
                'success' => false,
                'message' => $firstPayment['message'],
            ];
        }

        // Subscribe user
        $subscription = $this->authnet->createSubscription(
            $profile['customerProfileId'],
            $profile['paymentProfileId'],
            $subscriptionPlan['price'],
        );

        if (isset($subscription['subscriptionId'])) {

            // Calculate next date
            $nextDate = date('Y-m-d H:i:s', strtotime('+1 month'));

            // Update subscription in database
            $userSubscription['Model']->update(
                [
                    'subscription_id' => $request['plan'],
                    'price' => $subscriptionPlan['price'],
                    'status' => 'active',
                    'payment_card_id' => $primaryCard['id'],
                    'start_date' => date('Y-m-d H:i:s'),
                    'next_date' => $nextDate,
                    'end_date' => $nextDate,
                ]
            );

            // Set Authnet subscription ID
            $userSubscription['Model']->setMeta('authnet_sub_id', $subscription['subscriptionId']);

        } else {

            return [
                'success' => false,
                'message' => $subscription['message'],
            ];

        }

    }

    public function cancelSubscription($request)
    {

        $user_id = auth()->user()->id;
        $userSubscription = $this->userSubRepo->getByUserID($user_id);
        $subscriptionId = $userSubscription['Model']->getMeta('authnet_sub_id');

        if (!$subscriptionId) {
            return false;
        }

        // Calculate refund sum
        $refundSum = $this->calculateRefundSub(
            $userSubscription['start_date'],
            $userSubscription['end_date'],
            $userSubscription['price']
        );

        dd($refundSum, $userSubscription);

        $subRes = $this->authnet->cancelSubscription($subscriptionId);
        if (isset($subRes['success'])) {

            // Update subscription in database
            $this->userSubRepo->update(
                $userSubscription['id'],
                ['subscription_id' => null, 'status' => 'disabled']
            );

            // Delete subscription ID from meta
            $userSubscription['Model']->setMeta('authnet_sub_id', null);

            return [
                'success' => true,
                'message' => 'Subscription cancelled successfully',
            ];

        } else {
            return [
                'success' => false,
                'message' => $subRes['message'],
            ];
        }

    }

    public function destroyCard($card_id)
    {

        $card = $this->userCardRepo->getByID($card_id);
        if (!$card) {
            return false;
        }

        // Get meta
        $authnet_profile_id = $card['Meta']['authnet_profile_id'];

        // Delete customer profile and all payment profiles
        $customProfileRes = $this->authnet->deleteCustomerProfile($authnet_profile_id);

        // Delete record from database
        if (
            isset($customProfileRes['success']) ||
            $customProfileRes['code'] == 'E00040' // Profile not found or already deleted
        ) {
            $this->userCardRepo->delete($card_id);
        } else {
            return false;
        }

    }

    public function storeCard($request)
    {

        // Step 1: Create a customer profile and payment profile
        $paymentProfile = $this->createAuthnetProfile([
            'number' => $request['card_number'],
            'expiry' => $request['card_expiry_year'] . '-' . $request['card_expiry_month'],
            'cvv' => $request['card_cvv'],
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => auth()->user()->email,
        ]);

        // Step 2: Create record in the database
        if (
            isset($paymentProfile['customerProfileId']) ||
            isset($paymentProfile['paymentProfileId'])
        ) {

            // Split the expiration date into month and year
            $expireDate = $request['card_expiry_month'] . '/' . $request['card_expiry_year'];

            $card = $this->userCardRepo->create([
                'user_id' => auth()->user()->id,
                'card_holder_name' => $request['card_name'],
                'card_number' => $request['card_number'],
                'expiry_date' => $expireDate,
                'cvv' => $request['card_cvv'],
                'payment_method_id' => 1, // Authorize.net by default
            ]);

            // Set meta data
            $card['Model']->setMeta('authnet_profile_id', $paymentProfile['customerProfileId']);
            $card['Model']->setMeta('authnet_payment_profile_id', $paymentProfile['paymentProfileId']);

            return [
                'success' => true,
                'card' => $card,
            ];

        } else {
            return $paymentProfile;
        }

    }

    public function makePrimaryCard($card_id)
    {

        // Check if card exists
        $card = $this->userCardRepo->getByID($card_id);
        if (!$card) {
            return false;
        }

        // Make card primary
        $this->userCardRepo->makeCardPrimary($card_id);

    }

    public function createAuthnetProfile(array $cardData)
    {

        /*
        $cardData = [
            'number' => '4111111111111111',
            'expiry' => '2026-12',
            'cvv' => '123',
            'first_name' => '',
            'last_name' => '',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'country' => '',
            'email' => $email,
        ];
        */

        // Create customer profile
        $customerProfile = $this->authnet->createCustomerProfile($cardData['email']);

        // Create payment profile
        if ($customerProfile) {
            $paymentProfile = $this->authnet->createCustomerPaymentProfile(
                $customerProfile['profileId'],
                $cardData
            );
        }

        if (
            isset($customerProfile['profileId']) &&
            isset($paymentProfile['profileId'])
        ) {

            return $profile = [
                'customerProfileId' => $customerProfile['profileId'],
                'paymentProfileId' => $paymentProfile['profileId'],
            ];

        } else {
            return $paymentProfile;
        }

    }

    public function testCard()
    {

        $primaryCard = $this->userCardRepo->getPrimaryCard(auth()->user()->id);

        if (!$primaryCard) {
            dd('No primary card found');
        }

        $profile = [
            'customerProfileId' => $primaryCard['Meta']['authnet_profile_id'],
            'paymentProfileId' => $primaryCard['Meta']['authnet_payment_profile_id'],
        ];

        // Charge the card once
        $transaction = $this->authnet->chargeCustomerProfile(
            $profile['customerProfileId'],
            $profile['paymentProfileId'],
            49.99
        );
        dd($transaction);

        // Subscribe user
        $subscription = $this->authnet->createSubscription(
            $profile['customerProfileId'],
            $profile['paymentProfileId'],
            9.99
        );
        dd($subscription);

    }

    public function allSubscriptions()
    {

        $subscriptions = $this->authnet->getAllSubscriptions();
        dd($subscriptions);

    }

    public function cancelSubscriptions()
    {
        $this->authnet->cancelAllSubscriptions();
        dd('Cancelled all subscriptions');
    }

    public function calculateRefundSub($start_date, $end_date, $price): float
    {
        $start = Carbon::parse($start_date)->startOfDay();
        $end = Carbon::parse($end_date)->startOfDay();
        $now = Carbon::now()->startOfDay(); // Normalize current date to ignore time
    
        // No refund if subscription already ended
        if ($now->greaterThanOrEqualTo($end)) {
            return 0.0;
        }
    
        $totalDays = $start->diffInDays($end);
        $remainingDays = $now->diffInDays($end);
        $dailyRate = $price / $totalDays;
    
        return round($remainingDays * $dailyRate, 2);
    }    

}