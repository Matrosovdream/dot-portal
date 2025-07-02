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
use App\References\ServiceReferences;
use App\Repositories\Driver\DriverRepo;

class RequestUserActions {

    private $serviceGroupRepo;
    private $serviceRepo;
    private $requestRepo;
    private $userRepo;
    private $userCardRepo;
    private $authnet;
    private $userPaymentHistoryRepo;
    private $driverRepo;
    private $serviceRef;

    public function __construct()
    {
        $this->serviceGroupRepo = new RefServiceGroupRepo();
        $this->serviceRepo = new ServiceRepo();
        $this->requestRepo = new RequestRepo();
        $this->userRepo = new UserRepo();
        $this->userCardRepo = new UserPaymentCardRepo();
        $this->authnet = new AuthnetGateway;
        $this->userPaymentHistoryRepo = new UserPaymentHistoryRepo();

        // References
        $this->serviceRef = new ServiceReferences();
        $this->driverRepo = new DriverRepo();
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

        // Get form path for predefined forms
        if( $service['form_type'] == 'predefined' ) {

            $predefinedForm =  $this->serviceRef->getPredefinedForms()[ $service['form_id'] ];

            $formPath = $predefinedForm['path'] ?? null;
            $formClass = $predefinedForm['classProcess'] ?? null;

            if( $formClass ) {
                $refsClass = new $formClass();
                $formRefs = $refsClass->getReferences();
            }

            // Prepare values for predefined form
            $formFields = $refsClass->getFormFields();

            // Loop through and if old() exists, set it
            foreach( $formFields as $key => $field ) {
                $oldFields = old('fields');
                if( is_array($oldFields) && array_key_exists($key, $oldFields) ) {
                    $values[$key] = $oldFields[$key];
                } else {
                    $values[$key] = $field['value'] ?? null;
                }
            }
            
        }

        // Get referencences and prepare data
        $references = $this->getReferences();
        $drivers = [];
        foreach( $references['companyDrivers']['items'] as $driver ) {
            $drivers[] = [
                'value' => $driver['id'],
                'title' => $driver['firstname'] . ' ' . $driver['lastname'] . ' (' . $driver['email'] . ')',
            ];
        }
        $references['companyDrivers'] = $drivers;

        $data = [
            'title' => 'Services of ' . $groupslug,
            'group' => $group,
            'service' => $service,
            'formPath' => $formPath ?? null,
            'formRefs' => $formRefs ?? [],
            'values' => $values ?? [],
            'references' => $references,
        ];

        return $data;

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

        // Get form path for predefined forms
        if( $service['form_type'] == 'predefined' ) {

            $predefinedForm =  $this->serviceRef->getPredefinedForms()[ $service['form_id'] ];

            $formClass = $predefinedForm['classProcess'] ?? null;

            if( $formClass ) {
                $refsClass = new $formClass();
                $errors = $refsClass->validateFormData( $request->fields );
                if( !empty($errors) ) {
                    return [
                        'error' => true,
                        'message' => 'Form validation failed',
                        'errors' => $errors,
                    ];
                }
            }

        }

        // Create request
        $requestPayload = [
            'user_id' => auth()->user()->id,
            'status_id' => ($service['is_paid'] == 1) ? 2 : 1, // 1 - Processing, 2 - Waiting for payment
            'service_id' => $service['id'],
        ];
        $requestData = $this->requestRepo->create($requestPayload);

        // Sync field values, custom or predefined
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

    public function historyShow($request_id)
    {

        $request = $this->requestRepo->getById($request_id);

        if( !$request ) { return false; }

        $data = [
            'title' => 'Request details #' . $request_id,
            'formType' => $request['service']['form_type'] ?? 'custom',
            'request' => $request,
        ];

        if( $request['service']['form_type'] == 'predefined' ) {

            $predefinedForm =  $this->serviceRef->getPredefinedForms()[ $request['service']['form_id'] ];

            $data['predefinedForm'] = $predefinedForm;
            $data['predefinedValues'] = $request['predefinedValues'] ?? [];

            $formClass = $predefinedForm['classProcess'] ?? null;
            if( $formClass ) {
                $refsClass = new $formClass();

                $data['formFields'] = $refsClass->getFormFields();
                $data['formFields'] = $refsClass->matchFieldValues( $request['predefinedValues'] ?? [] );
            }

        }

        return $data;
    }

    public function historyShowPayments($request_id)
    {
        $payments = $this->userPaymentHistoryRepo->getAll( ['request_id' => $request_id], $paginate = 1000 );

        return [
            'title' => 'Request details #' . $request_id,
            'request' => $request = $this->requestRepo->getById($request_id),
            'paymentHistory' => $payments ?? [],
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

        if( !$requestData ) { return false; }

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
                'request_id' => $request_id,
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

    public function getReferences()
    {
        return [
            'companyDrivers' => $this->driverRepo->getAll(
                ['company_id' => auth()->user()->id], 
                $paginate = 1000
            ),
        ];
    }

}