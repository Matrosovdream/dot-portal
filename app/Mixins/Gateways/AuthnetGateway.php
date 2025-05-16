<?php

namespace App\Mixins\Gateways;


use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\contract\v1\PaymentProfileType;
use net\authorize\api\contract\v1\CustomerProfilePaymentType;
use net\authorize\api\contract\v1\CustomerProfileIdType;

class AuthnetGateway
{
    protected $merchantAuthentication;
    protected $environment;

    public function __construct()
    {
        $this->merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $this->merchantAuthentication->setName(config('services.authorize_net.api_login_id'));
        $this->merchantAuthentication->setTransactionKey(config('services.authorize_net.transaction_key'));

        $this->environment = config('services.authorize_net.sandbox', true)
            ? ANetEnvironment::SANDBOX
            : ANetEnvironment::PRODUCTION;
    }

    public function createCustomerProfile(string $email): array
    {
        $profile = new AnetAPI\CustomerProfileType();
        $profile->setEmail($email);
        $profile->setDescription("Profile for {$email}");
        $profile->setMerchantCustomerId(uniqid());

        $request = new AnetAPI\CreateCustomerProfileRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setProfile($profile);
        //$request->setValidationMode("testMode");

        $controller = new AnetController\CreateCustomerProfileController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response && $response->getMessages()->getResultCode() === "Ok") {
            return [
                "profileId" => $response->getCustomerProfileId(),
            ]; 
        } else {
            return $this->prepareResponseError($response);
        }

    }

    public function createCustomerPaymentProfile(string $customerProfileId, array $cardData): array
    {

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber( $this->prepareCardNumber($cardData['number']) );
        $creditCard->setExpirationDate( $this->prepareCardExpiry($cardData['expiry']) );
        $creditCard->setCardCode($cardData['cvv']);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setFirstName($cardData['first_name'] ?? '');
        $billTo->setLastName($cardData['last_name'] ?? '');
        $billTo->setAddress($cardData['address'] ?? '');
        $billTo->setCity($cardData['city'] ?? '');
        $billTo->setState($cardData['state'] ?? '');
        $billTo->setZip($cardData['zip'] ?? '');
        $billTo->setCountry($cardData['country'] ?? 'US');
        $billTo->setEmail($cardData['email'] ?? '');

        $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
        $paymentProfile->setCustomerType('individual');
        $paymentProfile->setPayment($payment);
        $paymentProfile->setBillTo($billTo);

        $request = new AnetAPI\CreateCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setCustomerProfileId($customerProfileId);
        $request->setPaymentProfile($paymentProfile);
        $request->setValidationMode("testMode");

        $controller = new AnetController\CreateCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response && $response->getMessages()->getResultCode() === "Ok") {
            return[
                'profileId' => $response->getCustomerPaymentProfileId()
            ];
        } else {
            return $this->prepareResponseError($response);
        }

    }

    public function deleteCustomerProfile(int $customerProfileId): array
    {
        $request = new AnetAPI\DeleteCustomerProfileRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setCustomerProfileId($customerProfileId);

        $controller = new AnetController\DeleteCustomerProfileController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response && $response->getMessages()->getResultCode() === "Ok") {
            return [
                'success' => true,
            ];
        } else {
            return $this->prepareResponseError($response);
        }
    }

    public function deletePaymentProfile(int $customerProfileId, int $paymentProfileId): array
    {
        $request = new AnetAPI\DeleteCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setCustomerProfileId($customerProfileId);
        $request->setCustomerPaymentProfileId($paymentProfileId);

        $controller = new AnetController\DeleteCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response && $response->getMessages()->getResultCode() === "Ok") {
            return [
                'success' => true
            ];
        } else {
            return $this->prepareResponseError($response);
        }
    }


    public function chargeCustomerProfile(string $customerProfileId, string $paymentProfileId, float $amount): array
    {
        $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
        $profileToCharge->setCustomerProfileId($customerProfileId);

        // FIXED: Use PaymentProfileType, not PaymentProfile
        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($paymentProfileId);
        $profileToCharge->setPaymentProfile($paymentProfile);

        $transactionRequest = new AnetAPI\TransactionRequestType();
        $transactionRequest->setTransactionType("authCaptureTransaction");
        $transactionRequest->setAmount($amount);
        $transactionRequest->setProfile($profileToCharge);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setTransactionRequest($transactionRequest);

        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response && $response->getMessages()->getResultCode() === "Ok") {
            return [
                'transactionId' => $response->getTransactionResponse()->getTransId()
            ];
        } else {
            return $this->prepareResponseError($response);
        }

    }

    public function createSubscription(string $customerProfileId, string $paymentProfileId, float $amount): array
    {
        // STEP 1: Get payment profile (to extract billing info)
        $getRequest = new AnetAPI\GetCustomerPaymentProfileRequest();
        $getRequest->setMerchantAuthentication($this->merchantAuthentication);
        $getRequest->setCustomerProfileId($customerProfileId);
        $getRequest->setCustomerPaymentProfileId($paymentProfileId);

        $getController = new AnetController\GetCustomerPaymentProfileController($getRequest);
        $getResponse = $getController->executeWithApiResponse($this->environment);

        if (
            !$getResponse ||
            $getResponse->getMessages()->getResultCode() !== "Ok"
        ) {
            throw new \Exception("Unable to fetch payment profile: " . $getResponse->getMessages()->getMessage()[0]->getText());
        }

        //dd($billTo);

        // STEP 2: Set subscription interval & schedule
        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength(1);
        $interval->setUnit("months");

        $schedule = new AnetAPI\PaymentScheduleType();
        $schedule->setInterval($interval);
        $schedule->setStartDate(new \DateTime(date('Y-m-d')));
        $schedule->setTotalOccurrences("9999");

        // STEP 3: Profile Info
        $profile = new AnetAPI\CustomerProfileIdType();
        $profile->setCustomerProfileId($customerProfileId);
        $profile->setCustomerPaymentProfileId($paymentProfileId);

        // STEP 4: Create subscription
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName("Laravel Subscription - " . uniqid());
        $subscription->setPaymentSchedule($schedule);
        $subscription->setAmount($amount);
        $subscription->setProfile($profile);
        //$subscription->setBillTo($billTo); // ✅ Critical line

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setSubscription($subscription);

        $controller = new AnetController\ARBCreateSubscriptionController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response && $response->getMessages()->getResultCode() === "Ok") {
            return [
                'subscriptionId' => $response->getSubscriptionId(),
            ];
        } else {
            return $this->prepareResponseError($response);
        }
    }


    public function cancelSubscription(string $subscriptionId): array
    {
        $request = new AnetAPI\ARBCancelSubscriptionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setSubscriptionId($subscriptionId);

        $controller = new AnetController\ARBCancelSubscriptionController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response && $response->getMessages()->getResultCode() === "Ok") {
            return [
                'success' => true,
            ];
        } else {
            return $this->prepareResponseError($response);
        }

    }

    public function refundTransaction(string $transactionId, float $amount, string $lastFourDigits): array
    {
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($lastFourDigits); // Only last 4 digits
        $creditCard->setExpirationDate("XXXX"); // Dummy expiration for refund

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        $transactionRequest = new AnetAPI\TransactionRequestType();
        $transactionRequest->setTransactionType("refundTransaction");
        $transactionRequest->setAmount($amount);
        $transactionRequest->setPayment($payment);
        $transactionRequest->setRefTransId($transactionId);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setTransactionRequest($transactionRequest);

        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response && $response->getMessages()->getResultCode() === "Ok") {
            return [
                'refunded' => true,
                'transactionId' => $response->getTransactionResponse()->getTransId()
            ];
        }

        return $this->prepareResponseError($response);
    }

    public function findCustomerProfileByEmail(string $email)
    {
        $request = new AnetAPI\GetCustomerProfileIdsRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);

        $controller = new AnetController\GetCustomerProfileIdsController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response && $response->getMessages()->getResultCode() === "Ok") {
            foreach ($response->getIds() as $id) {
                $profileRequest = new AnetAPI\GetCustomerProfileRequest();
                $profileRequest->setMerchantAuthentication($this->merchantAuthentication);
                $profileRequest->setCustomerProfileId($id);

                $profileController = new AnetController\GetCustomerProfileController($profileRequest);
                $profileResponse = $profileController->executeWithApiResponse($this->environment);

                if (
                    $profileResponse &&
                    $profileResponse->getMessages()->getResultCode() === "Ok" &&
                    strtolower($profileResponse->getProfile()->getEmail()) === strtolower($email)
                ) {
                    return $profileResponse->getProfile();
                }
            }
        }

        return null;
    }

    public function getAllSubscriptions(int $limit = 1000): array
    {
        $request = new AnetAPI\ARBGetSubscriptionListRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);

        $request->setSearchType("subscriptionActive"); // or "subscriptionExpiringThisMonth", etc.
        $request->setSorting(new AnetAPI\ARBGetSubscriptionListSortingType([
            'orderBy' => 'id',
            'orderDescending' => false,
        ]));
        $request->setPaging(new AnetAPI\PagingType([
            'limit' => $limit,
            'offset' => 1,
        ]));

        $controller = new AnetController\ARBGetSubscriptionListController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        $results = [];
        if ($response && $response->getMessages()->getResultCode() === "Ok") {
            foreach ($response->getSubscriptionDetails() as $subscription) {
                $results[] = [
                    'id' => $subscription->getId(),
                    'name' => $subscription->getName(),
                    'status' => $subscription->getStatus(),
                    'amount' => $subscription->getAmount(),
                    'profileId' => $subscription->getCustomerProfileId(),
                ];
            }
        }

        return $results;
    }

    public function cancelAllSubscriptions(): array
    {
        $subscriptions = $this->getAllSubscriptions();
        $results = [];

        foreach ($subscriptions as $subscription) {
            $request = new AnetAPI\ARBCancelSubscriptionRequest();
            $request->setMerchantAuthentication($this->merchantAuthentication);
            $request->setSubscriptionId($subscription['id']);

            $controller = new AnetController\ARBCancelSubscriptionController($request);
            $response = $controller->executeWithApiResponse($this->environment);

            $results[] = [
                'id' => $subscription['id'],
                'success' => $response && $response->getMessages()->getResultCode() === "Ok",
                'message' => $response->getMessages()->getMessage()[0]->getText() ?? 'Unknown',
            ];
        }

        return $results;
    }


    private function prepareResponseError( object $response ): array
    {
        return [
            'error' => true,
            'code' => $response->getMessages()->getMessage()[0]->getCode(),
            'message' => $response->getMessages()->getMessage()[0]->getText(),
        ];
    } 

    private function prepareCardExpiry(string $expiry): string
    {
        // Split into month and year
        $parts = explode('-', $expiry);

        // Make month 2 digits if the month is a single digit
        if (strlen($parts[1]) === 1) {
            $parts[1] = '0' . $parts[1];
        }

        return $parts[0] . '-' . $parts[1];

    }

    private function prepareCardNumber(string $cardNumber): string
    {
        // Remove any non-digit characters
        return preg_replace('/\D/', '', $cardNumber);
    }

}
