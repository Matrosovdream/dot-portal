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

    public function createCustomerPaymentProfile(array $cardData, string $email): array
    {
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardData['number']);
        $creditCard->setExpirationDate($cardData['expiry']); // YYYY-MM
        $creditCard->setCardCode($cardData['cvv']);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        // Add billing info (important!)
        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setFirstName($cardData['first_name'] ?? 'Test');
        $billTo->setLastName($cardData['last_name'] ?? 'User');
        $billTo->setAddress($cardData['address'] ?? '123 Test Street');
        $billTo->setCity($cardData['city'] ?? 'Testville');
        $billTo->setState($cardData['state'] ?? 'CA');
        $billTo->setZip($cardData['zip'] ?? '12345');
        $billTo->setCountry($cardData['country'] ?? 'USA');

        $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
        $paymentProfile->setCustomerType('individual');
        $paymentProfile->setPayment($payment);
        $paymentProfile->setBillTo($billTo);

        $profile = new AnetAPI\CustomerProfileType();
        $profile->setEmail($email);
        $profile->setPaymentProfiles([$paymentProfile]);

        $request = new AnetAPI\CreateCustomerProfileRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setProfile($profile);
        $request->setValidationMode("testMode");

        $controller = new AnetController\CreateCustomerProfileController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response && $response->getMessages()->getResultCode() === "Ok") {
            return [
                'customerProfileId' => $response->getCustomerProfileId(),
                'paymentProfileId' => $response->getCustomerPaymentProfileIdList()[0],
            ];
        }

        throw new \Exception($response->getMessages()->getMessage()[0]->getText());
    }

    public function chargeCustomerProfile(string $customerProfileId, string $paymentProfileId, float $amount): string
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
            return $response->getTransactionResponse()->getTransId();
        }

        throw new \Exception($response->getMessages()->getMessage()[0]->getText());
    }


    public function createSubscription(string $customerProfileId, string $paymentProfileId, float $amount): string
    {
        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength(1);
        $interval->setUnit("months");

        $schedule = new AnetAPI\PaymentScheduleType();
        $schedule->setInterval($interval);
        $schedule->setStartDate(new \DateTime(date('Y-m-d')));
        $schedule->setTotalOccurrences("9999");

        $profile = new AnetAPI\CustomerProfileIdType();
        $profile->setCustomerProfileId($customerProfileId);
        $profile->setCustomerPaymentProfileId($paymentProfileId);

        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName("Recurring Billing");
        $subscription->setPaymentSchedule($schedule);
        $subscription->setAmount($amount);
        $subscription->setProfile($profile);

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setSubscription($subscription);

        $controller = new AnetController\ARBCreateSubscriptionController($request);
        $response = $controller->executeWithApiResponse($this->environment);

        if ($response && $response->getMessages()->getResultCode() === "Ok") {
            return $response->getSubscriptionId();
        }

        throw new \Exception($response->getMessages()->getMessage()[0]->getText());
    }
}
