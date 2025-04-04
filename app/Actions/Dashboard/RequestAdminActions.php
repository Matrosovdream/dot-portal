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

    public function show($service_id)
    {
        $request = $this->requestRepo->getById($service_id);
//dd($request);
        return [
            'title' => 'Request details #' . $service_id,
            'request' => $request,
            'references' => $this->getReferences()
        ];
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