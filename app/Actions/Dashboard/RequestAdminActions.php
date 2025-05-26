<?php
namespace App\Actions\Dashboard;


use App\Repositories\References\RefServiceGroupRepo;
use App\Repositories\Service\ServiceRepo;
use App\Repositories\Request\RequestRepo;
use App\Repositories\References\RefRequestStatusRepo;
use App\Repositories\User\UserPaymentHistoryRepo;
use App\References\ServiceReferences;

class RequestAdminActions {

    private $serviceGroupRepo;
    private $serviceRepo;
    private $requestRepo;

    private $refRequestStatus;
    private $userPaymentHistoryRepo;
    private $serviceRef;

    public function __construct()
    {
        $this->serviceGroupRepo = new RefServiceGroupRepo();
        $this->serviceRepo = new ServiceRepo();
        $this->requestRepo = new RequestRepo();
        $this->userPaymentHistoryRepo = new UserPaymentHistoryRepo();

        // References
        $this->refRequestStatus = new RefRequestStatusRepo();
        $this->serviceRef = new ServiceReferences();
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
        $payments = $this->userPaymentHistoryRepo->getAll( ['request_id' => $request_id], $paginate = 1000 );

        if( !$request ) { return false;}

        $data = [
            'title' => 'Request details #' . $request_id,
            'request' => $request,
            'request_id' => $request_id,
            'fieldValues' => $request['fieldValues'] ?? [],
            'paymentHistory' => $payments ?? [],
            'formType' => $request['service']['form_type'] ?? 'custom',
            'references' => $this->getReferences()
        ];

        if( $request['service']['form_type'] == 'predefined' ) {
            $data['predefinedForm'] = $this->serviceRef->getPredefinedForms()[ $request['service']['form_id'] ];
            $data['predefinedValues'] = $request['predefinedValues'] ?? [];
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

        if ($request) {
            // Sync field values
            $this->requestRepo->syncFieldValues( $request_id, $fields=$data['fields'] );
        }

        // Return updated request entity
        return $this->requestRepo->getById($request_id);
    }

    public function destroy($group_id)
    {
        return $this->requestRepo->delete($group_id);
    }

    public function getReferences()
    {
        return [
            'requestStatus' => $this->refRequestStatus->getAll(),
        ];
    }

}