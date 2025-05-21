<?php
namespace App\Actions\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;

use App\Repositories\References\RefServiceGroupRepo;
use App\Repositories\Service\ServiceRepo;
use App\Repositories\Request\RequestRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\User\UserPaymentCardRepo;
use App\Mixins\Gateways\AuthnetGateway;
use App\Repositories\User\UserPaymentHistoryRepo;

class RequestUserActions {

    private $serviceGroupRepo;
    private $serviceRepo;
    private $requestRepo;
    private $userRepo;
    private $userCardRepo;
    private $authnet;
    private $userPaymentHistoryRepo;

    public function __construct()
    {
        $this->serviceGroupRepo = new RefServiceGroupRepo();
        $this->serviceRepo = new ServiceRepo();
        $this->requestRepo = new RequestRepo();
        $this->userRepo = new UserRepo();
        $this->userCardRepo = new UserPaymentCardRepo();
        $this->authnet = new AuthnetGateway;
        $this->userPaymentHistoryRepo = new UserPaymentHistoryRepo();
    }

    public function showGroup( $groupslug )
    {
        $group = $this->serviceGroupRepo->getBySlug($groupslug);
        if( $group ) {
            $services = $this->serviceRepo->getAll(
                ['group_id' => $group['id'], 'status_id' => 1], 
                $paginate = 1000
            );
        }

        return [
            'title' => 'Services of ' . $groupslug,
            'group' => $group,
            'services' => $services
        ];

    }

    public function show( $groupslug, $serviceslug )
    {
        // Group
        $group = $this->serviceGroupRepo->getBySlug($groupslug);
        if( $group ) {
            $services = $this->serviceRepo->getByGroupID($group['id']);
        }

        // Service
        $service = $this->serviceRepo->getBySlug($serviceslug);

        return [
            'title' => 'Services of ' . $groupslug,
            'group' => $group,
            'service' => $service
        ];
    }

    public function update($group_id, $request)
    {
        return $this->serviceGroupRepo->update($group_id, $request);
    }

    public function create()
    {
        $data = [
            'title' => 'Create new group'
        ];

        return $data;
    }

    public function store($request)
    {
        $data = $this->serviceGroupRepo->create($request);
        return $data;
    }

    public function storeRequest($groupslug, $serviceslug, Request $request)
    {

        $service = $this->serviceRepo->getBySlug($serviceslug);

        // Create request
        $requestPayload = [
            'user_id' => auth()->user()->id,
            'status_id' => ($service['is_paid'] == 1) ? 2 : 1, // 1 - Processing, 2 - Waiting for payment
            'service_id' => $service['id'],
        ];
        $requestData = $this->requestRepo->create($requestPayload);

        // Attach field values
        $this->requestRepo->syncFieldValues( $requestData['id'], $request->fields );
        
        // Return updated request entity
        return $this->requestRepo->getById($requestData['id']);
    }

    public function history()
    {
        $requests = $this->requestRepo->getAll( ['user_id' => auth()->user()->id] );

        return [
            'title' => 'My requests',
            'requests' => $requests
        ];
    }

    public function historyShow($service_id)
    {
        $request = $this->requestRepo->getById($service_id);

        return [
            'title' => 'Request details #' . $service_id,
            'request' => $request
        ];
    }

    public function historyShowPay($request_id)
    {
        $request = $this->requestRepo->getById($request_id);
        $service = $this->serviceRepo->getById($request['service']['id']);
        $user = $this->userRepo->getByID( auth()->user()->id );

        return [
            'title' => 'Request payment #' . $request_id,
            'request' => $request,
            'service' => $service,
            'user' => $this->userRepo->getByID( auth()->user()->id ),
            'paymentCards' => $user['paymentCards'],
        ];
    }

    public function historyShowPayProcess( $request, $request_id )
    {

        $paymentCard = $this->userCardRepo->getById( $request['payment_method'] );
        $requestData = $this->requestRepo->getById($request_id);

        if( !$requestData ) {
            return false;
        }

        $price = $requestData['service']['price'];

        // Prepare payment data
        $profile = [
            'customerProfileId' => $paymentCard['Meta']['authnet_profile_id'],
            'paymentProfileId' => $paymentCard['Meta']['authnet_payment_profile_id'],
        ];

        //$profile['customerProfileId'] = 11111;

        // Charge the customer
        $paymentRes = $this->authnet->chargeCustomerProfile(
            $profile['customerProfileId'],
            $profile['paymentProfileId'],
            $price,
        );

        if( !isset($paymentRes['error']) ) {

            // Update request status
            $requestData['Model']->update([
                'status_id' => 1, // 1 - Processing
                'is_paid' => 1,
            ]);

            // Create payment history record
            $this->userPaymentHistoryRepo->create([
                'user_id' => auth()->user()->id,
                'payment_method_id' => 1,
                //'subscription_id' => null,
                'type' => 'request_payment',
                'amount' => $price,
                'payment_date' => date('Y-m-d H:i:s'),
                'transaction_id' => $paymentRes['transactionId'],
                'status' => 'success',
                'notes' => 'Payment for the service "' . $requestData['service']['name'] . '"',
            ]);

            return [
                'success' => true
            ];

        } else {

            return [
                'error' => true,
                'message' => $paymentRes['message'],
                'code' => $paymentRes['code'],
            ];

        }


    }

    public function destroy($group_id)
    {
        return $this->serviceGroupRepo->delete($group_id);
    }

}