<?php
namespace App\Mixins\Gateways;

interface GatewayInterface {

    public function setParams($params);

    public function charge();

    public function setCreditCard();

    public function processResponse();

    public function makeTransactionId();

    public function setError($error);

    public function getErrors();

    public function isErrors();

    public function getResponse();

}