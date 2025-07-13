<?php
namespace App\Actions\Dashboard;


use App\Repositories\References\RefServiceGroupRepo;
use App\Repositories\Service\ServiceRepo;
use App\Repositories\Request\RequestRepo;
use App\Repositories\References\RefRequestStatusRepo;
use App\Repositories\User\UserPaymentHistoryRepo;
use App\References\ServiceReferences;
use App\Repositories\Driver\DriverRepo;

class RequestAdminActions {

    private $serviceGroupRepo;
    private $serviceRepo;
    private $requestRepo;

    private $refRequestStatus;
    private $userPaymentHistoryRepo;
    private $serviceRef;
    private $driverRepo;

    public function __construct()
    {
        $this->serviceGroupRepo = new RefServiceGroupRepo();
        $this->serviceRepo = new ServiceRepo();
        $this->requestRepo = new RequestRepo();
        $this->userPaymentHistoryRepo = new UserPaymentHistoryRepo();

        // References
        $this->refRequestStatus = new RefRequestStatusRepo();
        $this->serviceRef = new ServiceReferences();
        $this->driverRepo = new DriverRepo();
    }

    public function index()
    {
        $requests = $this->requestRepo->getAll( [] );

        return [
            'title' => 'All requests',
            'requests' => $requests
        ];
    }

    public function show($request_id)
    {
        $request = $this->requestRepo->getById($request_id);
        $service = $request['service'] ?? [];
        $payments = $this->userPaymentHistoryRepo->getAll( ['request_id' => $request_id], $paginate = 1000 );

        if( !$request ) { return false;}

        $data = [
            'title' => 'Request details #' . $request_id,
            'request' => $request,
            'request_id' => $request_id,
            'fieldValues' => $request['fieldValues'] ?? [],
            'paymentHistory' => $payments ?? [],
            'formType' => $request['service']['form_type'] ?? 'custom',
            'formId' => $request['service']['form_id'] ?? null,
            'references' => $this->getReferences( $request['user']['id'] ?? null )
        ];

                // Get form path for predefined forms
                if( $service['form_type'] == 'predefined' ) {

                    $predefinedForm =  $this->serviceRef->getPredefinedForms()[ $service['form_id'] ];
        
                    $formPath = $predefinedForm['path'] ?? null;
                    $formClass = $predefinedForm['classProcess'] ?? null;
        
                    if( $formClass ) {
                        $refsClass = new $formClass();
                        $refsClass->setRequestData( $request['predefinedValues'] );
                        $formRefs = $refsClass->getReferences();
                    }
        
                    // Prepare values for predefined form
                    $data['formFields'] = $refsClass->getFormFields();
                    $data['formFields'] = $refsClass->matchFieldValues( $request['predefinedValues'] ?? [] );
                    $data['formValues'] = $request['predefinedValues']['Mapped'] ?? [];
                    $data['formRefs'] = $formRefs ?? [];
                    $data['formPath'] = $formPath;

                    // Update values, eg. for hazardous materials into array
                    $data['formValues'] = $refsClass->prepareFieldsFromDB( $data['formValues'] );
                    
                }

        return $data;

    }

    public function updateStatus($data, $service_id)
    {
        $request = $this->requestRepo->getById($service_id);

        if ($request) {
            $request['Model']->status_id = $data['status_id'];
            $request['Model']->save();
        }

        return $request;
    }

    public function updateFields($data, $request_id)
    {
        $request = $this->requestRepo->getById($request_id); 
        if (!$request) {
            return false; // Request not found
        }

        // Sync field values
        $this->requestRepo->syncFieldValues($request_id, $fields = $data['fields']);

        // Return updated request entity
        return $this->requestRepo->getById($request_id);
    }

    public function destroy($group_id)
    {
        return $this->requestRepo->delete($group_id);
    }

    public function getReferences( $user_id=null )
    {
        return [
            'requestStatus' => $this->refRequestStatus->getAll(),
            /*'companyDrivers' => $this->driverRepo->getAll(
                ['company_id' => $user_id], 
                $paginate = 1000
            ),*/
        ];
    }

}