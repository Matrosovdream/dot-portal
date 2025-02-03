<?php
namespace App\Mixins\Order;

use App\Models\Order;
use App\Mixins\Gateways\AuthnetGateway;

class OrderPaymentProcesser
{
    public $order_id;
    public $params;
    public $order;
    public $paymentMethod;
    public $paymentProcessor;
    private $gateway_data;

    public function __construct($order_id, $params=[])
    {
        $this->order_id = $order_id;
        $this->params = $params;

        $this->order = $this->getOrder();
        $this->paymentMethod = $this->getPaymentMethod();
        $this->paymentProcessor = $this->getPaymentProcessor();

    }

    public function charge()
    {

        $errors = [];
        
        $data = [
            'cart_data' => $this->params['cart_data'],
            'order_data' => $this->prepareOrderData(),
            'customer_data' => $this->order->customerFields(),
        ];

        // Set params
        $this->paymentProcessor->setParams($data);

        // Process payment
        $this->paymentProcessor->charge();

        // Check if there are any errors
        if( count($this->paymentProcessor->getErrors()) > 0 ) {
            $errors = $this->paymentProcessor->getErrors();
            $status = 'failed';
        } else {
            $status = 'success';
        }

        // Set order paid
        if( $status == 'success') {
            $this->order->setPaid();
        }

        // Save transation into database
        $data = [
            'user_id' => $this->order->user_id,
            'payment_gateway_id' => $this->order->payment_method_id,
            'transaction_id' => $this->paymentProcessor->transaction_id,
            'amount' => $this->order->getTotal(),
            'currency' => $this->order->currency,
            'status' => $status,
            'payment_response' => $this->paymentProcessor->getResponse(),
            'order_id' => $this->order_id,
        ];
        $this->saveTransaction( $data );

        return [
            'status' => $status,
            'errors' => $errors
        ];

    }

    public function refund()
    {
        //$this->paymentProcessor->refund();
    }

    public function saveTransaction($data)
    {
        $this->order->payments()->create( $data );
    }

    private function getOrder()
    {
        return Order::find($this->order_id);
    }

    private function prepareOrderData()
    {
        return [
            'amount' => $this->order->getTotal(),
            'currency' => 'USD'
        ];
    }
    

    public function getPaymentMethod()
    {
        return $this->order->paymentMethod;
    }

    public function getPaymentProcessor() {

        if( $this->paymentMethod->id == 1 ) {
            return new AuthnetGateway();
        } 

    }


}