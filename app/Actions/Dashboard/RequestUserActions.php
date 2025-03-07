<?php
namespace App\Actions\Dashboard;

use Illuminate\Http\Request;

use App\Helpers\adminSettingsHelper;
use App\Repositories\References\RefServiceGroupRepo;
use App\Repositories\Service\ServiceRepo;
use App\Repositories\Request\RequestRepo;

class RequestUserActions {

    private $serviceGroupRepo;
    private $serviceRepo;
    private $requestRepo;

    public function __construct()
    {
        $this->serviceGroupRepo = new RefServiceGroupRepo();
        $this->serviceRepo = new ServiceRepo();
        $this->requestRepo = new RequestRepo();
    }

    public function showGroup( $groupslug )
    {
        $group = $this->serviceGroupRepo->getBySlug($groupslug);
        if( $group ) {
            $services = $this->serviceRepo->getByGroupID($group['id']);
        }
        
        return [
            'title' => 'Services of ' . $groupslug,
            'group' => $group,
            'services' => $services,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
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

        //dd($service);

        return [
            'title' => 'Services of ' . $groupslug,
            'group' => $group,
            'service' => $service,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];
    }

    public function update($group_id, $request)
    {
        return $this->serviceGroupRepo->update($group_id, $request);
    }

    public function create()
    {
        $data = [
            'title' => 'Create new group',
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
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
            'status_id' => 1,
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
            'requests' => $requests,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];
    }

    public function historyShow($service_id)
    {
        $request = $this->requestRepo->getById($service_id);

        return [
            'title' => 'Request details #' . $service_id,
            'request' => $request,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];
    }

    public function destroy($group_id)
    {
        return $this->serviceGroupRepo->delete($group_id);
    }

}