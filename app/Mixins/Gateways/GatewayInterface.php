<?php
namespace App\Mixins\Gateways;

interface GatewayInterface {

    public function setParams(array $params);

    public function charge();

    public function setCreditCard();

    public function getCreditCard();

    public function handleResponse();

    public function createSubscription();

    public function updateSubscription();

    public function cancelSubscription();

    public function recordError(string $error);

    public function getErrors();

    public function hasErrors();

    public function getResponse();

    public function makeTransactionId();

}