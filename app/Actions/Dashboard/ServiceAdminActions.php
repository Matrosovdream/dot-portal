<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\Service\ServiceRepo;
use App\Repositories\References\RefServiceGroupRepo;

class ServiceAdminActions {

    private $serviceRepo;
    private $serviceGroupRepo;

    public function __construct()
    {
        $this->serviceRepo = new ServiceRepo();
        $this->serviceGroupRepo = new RefServiceGroupRepo();
    }

    public function index()
    {

        // Get drivers by user
        $services = $this->serviceRepo->getAll( 
            [], 
            $paginate = 10 
        );

        $data = [
            'title' => 'Services',
            'services' => $services,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function show($service_id)
    {
        $service = $this->serviceRepo->getByID($service_id);

        $data = [
            'title' => 'Service details',
            'service' => $service,
            'references' => $this->getReferences(),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function update($service_id, $request)
    {
        $data = $this->serviceRepo->update($service_id, $request);

        return $data;
    }

    public function create()
    {
        $data = [
            'title' => 'Create driver',
            'references' => $this->getReferences(),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function store($request)
    {
        $data = $this->serviceRepo->create($request);

        return $data;
    }

    public function delete($service_id)
    {
        $data = $this->serviceRepo->delete($service_id);

        return $data;
    }

    public function getReferences()
    {
        return [
            'serviceGroups' => $this->serviceGroupRepo->getAll([], $paginate = 10000),
        ];
    }

}