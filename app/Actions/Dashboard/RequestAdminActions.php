<?php
namespace App\Actions\Dashboard;


use App\Repositories\References\RefServiceGroupRepo;
use App\Repositories\Service\ServiceRepo;
use App\Repositories\Request\RequestRepo;
use App\Repositories\References\RefRequestStatusRepo;

class RequestAdminActions {

    private $serviceGroupRepo;
    private $serviceRepo;
    private $requestRepo;

    private $refRequestStatus;

    public function __construct()
    {
        $this->serviceGroupRepo = new RefServiceGroupRepo();
        $this->serviceRepo = new ServiceRepo();
        $this->requestRepo = new RequestRepo();

        // References
        $this->refRequestStatus = new RefRequestStatusRepo();
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

        return [
            'title' => 'Request details #' . $request_id,
            'request' => $request,
            'request_id' => $request_id,
            'fieldValues' => $request['fieldValues'] ?? [],
            'payments' => [],
            'references' => $this->getReferences()
        ];
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