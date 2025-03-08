<?php

namespace App\Mixins\Gateways;

use Omnipay\Omnipay;
use Exception;
use Omnipay\Common\CreditCard;

class AuthnetGateway implements GatewayInterface
{
    /**
     * Parameters for transactions and subscriptions.
     *
     * @var array
     */
    protected $params = [];

    /**
     * The Omnipay gateway instance.
     *
     * @var \Omnipay\Common\AbstractGateway
     */
    protected $gateway;

    /**
     * The latest gateway response.
     *
     * @var mixed
     */
    protected $response;

    /**
     * The transaction or subscription ID.
     *
     * @var string|null
     */
    protected $transactionId = null;

    /**
     * List of error messages.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Status flag for successful operation.
     *
     * @var bool
     */
    protected $success = false;

    /**
     * AuthnetGateway constructor.
     */
    public function __construct()
    {
        $this->gateway = Omnipay::create('AuthorizeNetApi_Api');
        $this->gateway->setAuthName(env('ANET_API_LOGIN_ID'));
        $this->gateway->setTransactionKey(env('ANET_TRANSACTION_KEY'));
        $this->gateway->setTestMode(true); // Set to false for production
    }

    /**
     * Set the parameters for a transaction or subscription.
     *
     * @param array $params
     * @return void
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * Process a one-time charge.
     *
     * @return void
     */
    public function charge(): void
    {
        try {
            $creditCard = $this->getCreditCard();

            $response = $this->gateway->purchase([
                'amount'   => $this->params['order_data']['amount'],
                'currency' => $this->params['order_data']['currency'],
                'card'     => $creditCard
            ])->send();

            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->recordError($e->getMessage());
        }
    }

    /**
     * Create and return a CreditCard instance using the provided cart data.
     *
     * @return \Omnipay\Common\CreditCard
     */
    public function setCreditCard()
    {
        return new \Omnipay\Common\CreditCard([
            'number' => $this->params['cart_data']['cc_number'],
            'expiryMonth' => $this->params['cart_data']['expiry_month'],
            'expiryYear' => $this->params['cart_data']['expiry_year'],
            'cvv' => $this->params['cart_data']['cvv'],
        ]);
    }

    /**
     * Create and return a CreditCard instance using the provided cart data.
     *
     * @return CreditCard
     */
    protected function getCreditCard(): CreditCard
    {
        return new CreditCard([
            'number'      => $this->params['cart_data']['cc_number'],
            'expiryMonth' => $this->params['cart_data']['expiry_month'],
            'expiryYear'  => $this->params['cart_data']['expiry_year'],
            'cvv'         => $this->params['cart_data']['cvv'],
        ]);
    }

    /**
     * Handle the gateway response by setting the response, transaction ID, and success flag.
     *
     * @param mixed $response
     * @return void
     */
    protected function handleResponse($response): void
    {
        $this->response = $response;
        $data = $response->getData();

        // Extract the transaction ID if available
        if (isset($data['transactionResponse']['transId'])) {
            $this->transactionId = $data['transactionResponse']['transId'];
        }

        if ($response->isSuccessful()) {
            $this->success = true;
        } else {
            $this->recordError($response->getMessage());
        }
    }

    /**
     * Create a subscription (recurring payment).
     *
     * Expects subscription data in $this->params['subscription_data'] with keys:
     * - amount
     * - currency
     * - interval_unit (e.g., 'months')
     * - interval_length (e.g., 1)
     * - start_date (Y-m-d)
     * - total_occurrences
     * - trial_occurrences (optional)
     *
     * @return void
     */
    public function createSubscription(): void
    {
        try {
            $subscriptionData = [
                'amount'           => $this->params['subscription_data']['amount'],
                'currency'         => $this->params['subscription_data']['currency'],
                'intervalUnit'     => $this->params['subscription_data']['interval_unit'],
                'intervalLength'   => $this->params['subscription_data']['interval_length'],
                'startDate'        => $this->params['subscription_data']['start_date'],
                'totalOccurrences' => $this->params['subscription_data']['total_occurrences'],
                'trialOccurrences' => $this->params['subscription_data']['trial_occurrences'] ?? 0,
                'card'             => $this->getCreditCard(),
            ];

            $response = $this->gateway->createSubscription($subscriptionData)->send();
            $this->response = $response;

            if ($response->isSuccessful()) {
                $data = $response->getData();
                $this->transactionId = $data['subscriptionId'] ?? null;
                $this->success = true;
            } else {
                $this->recordError($response->getMessage());
            }
        } catch (Exception $e) {
            $this->recordError($e->getMessage());
        }
    }

    /**
     * Update an existing subscription.
     *
     * @param string $subscriptionId The subscription identifier to update.
     * @param array  $updateData     Subscription data to update.
     * @return void
     */
    public function updateSubscription(string $subscriptionId, array $updateData): void
    {
        try {
            $updateData['subscriptionId'] = $subscriptionId;
            $response = $this->gateway->updateSubscription($updateData)->send();
            $this->response = $response;

            if ($response->isSuccessful()) {
                $this->success = true;
            } else {
                $this->recordError($response->getMessage());
            }
        } catch (Exception $e) {
            $this->recordError($e->getMessage());
        }
    }

    /**
     * Cancel an existing subscription.
     *
     * @param string $subscriptionId The subscription identifier to cancel.
     * @return void
     */
    public function cancelSubscription(string $subscriptionId): void
    {
        try {
            $response = $this->gateway->cancelSubscription([
                'subscriptionId' => $subscriptionId,
            ])->send();
            $this->response = $response;

            if ($response->isSuccessful()) {
                $this->success = true;
            } else {
                $this->recordError($response->getMessage());
            }
        } catch (Exception $e) {
            $this->recordError($e->getMessage());
        }
    }

    /**
     * Record an error message.
     *
     * @param string $error
     * @return void
     */
    public function recordError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * Get all recorded errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if any errors have been recorded.
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Retrieve the full response data.
     *
     * @return mixed|null
     */
    public function getResponse()
    {
        return isset($this->response) ? $this->response->getData() : null;
    }

    /**
     * Get the transaction or subscription ID.
     *
     * @return string|null
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * Generate a unique transaction ID.
     *
     * @return string
     */
    public function makeTransactionId(): string
    {
        return uniqid();
    }

    /**
     * Check if the last operation was successful.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->success;
    }

}
