<?php
namespace App\Actions\Dashboard;

use App\Models\Service;
use App\Helpers\adminSettingsHelper;
use App\Repositories\Service\ServiceRepo;

class ServiceAdminActions {

    private $serviceRepo;

    public function __construct()
    {
        $this->serviceRepo = new ServiceRepo();
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
        $driver = $this->serviceRepo->getByID($service_id);

        $data = [
            'title' => 'Service details',
            'driver' => $driver,
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
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function store($request)
    {
        $data = $this->serviceRepo->create($request);

        return $data;
    }

    public function destroy($service_id)
    {
        $data = $this->serviceRepo->delete($service_id);

        return $data;
    }

}