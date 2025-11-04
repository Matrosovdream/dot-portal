<?php
namespace App\Actions\Dashboard;

use App\Repositories\Order\OrderRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\User\UserPaymentCardRepo;
use App\Repositories\User\UserPaymentHistoryRepo;
use App\Mixins\Gateways\AuthnetGateway;


class OrderActions {

    public function __construct(
        private OrderRepo $orderRepo,
        private UserRepo $userRepo,
        private UserPaymentCardRepo $userCardRepo,
        private AuthnetGateway $authnet,
        private UserPaymentHistoryRepo $userPaymentHistoryRepo,

    )
    {

    }

    public function showPay( $order_id, $request )
    {
//dd($this->orderRepo->getById( $order_id ));

        $user = $this->userRepo->getByID( auth()->user()->id );

        return [
            'title' => 'Pay for Order #' . $order_id,
            'order_id' => $order_id,
            'order' => $this->orderRepo->getById( $order_id ),
            'paymentCards' => $user['paymentCards'],
        ];

    }

    public function processPay( $order_id, $request )
    {

        $order = $this->orderRepo->getById( $order_id );
        
        if (!$order) {
            return [
                'success' => false,
                'message' => 'Order not found.',
            ];
        }

        // Payment processing logic would go here
        $amount = $order['amount'];
        $paymentUserCard = $request['payment_method'];
        $user_id = $order['user_id'];

        // Payment card 
        $paymentCard = $this->userCardRepo->getById( $paymentUserCard );

        $paymentRes = $this->payWithUserCard( $user_id, $paymentCard, $amount, $order );

        if( !isset($paymentRes['error']) ) {

            $this->updateSuccessfulPayment( $paymentRes, $order, $amount );

            return [
                'success' => true,
                'message' => 'Payment processed successfully.',
            ];

        } else {

            return [
                'success' => false,
                'message' => 'Payment failed: ' . $paymentRes['error'],
            ];

        }

    }

    private function updateSuccessfulPayment( $paymentRes, $order, $amount ) {

        // Update order status to paid
        $this->orderRepo->update( $order['id'], [
            'status_id' => 3, // Completed status
        ]);
        
        // Create payment history record
        $this->userPaymentHistoryRepo->create([
            'user_id' => $order['user_id'],
            'payment_method_id' => $order['payment_method_id'],
            'type' => 'chm_query_payment',
            'amount' => $amount,
            'payment_date' => date('Y-m-d H:i:s'),
            'transaction_id' => $paymentRes['transactionId'],
            'status' => 'success',
            'notes' => $order['item']['item_name'] ?? ''
        ]);

    }

    private function payWithUserCard( $user_id, $paymentCard, $amount, $order )
    {

        //dd($user_id, $paymentCard, $amount);

        // Check if this card belongs to the user
        if ( $paymentCard['user_id'] != $user_id ) {
            return [
                'success' => false,
                'message' => 'Payment card does not belong to the user.',
            ];
        }

        // Prepare payment data
        $profile = [
            'customerProfileId' => $paymentCard['Meta']['authnet_profile_id'],
            'paymentProfileId' => $paymentCard['Meta']['authnet_payment_profile_id'],
        ];

        // Charge the customer
        return $this->authnet->chargeCustomerProfile(
            $profile['customerProfileId'],
            $profile['paymentProfileId'],
            $amount,
        );

    }


}