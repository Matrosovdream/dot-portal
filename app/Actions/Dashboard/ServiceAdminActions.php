<?php
namespace App\Actions\Dashboard;

use App\Models\Service;
use App\Helpers\adminSettingsHelper;

class ServiceAdminActions {

    public function __construct()
    {
        // 
    }

    public function index()
    {
        $data = [
            'title' => 'Services list',
            'services' => Service::paginate(10),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function show( $service )
    {
        $data = [
            'title' => 'Service details #' . $service->id,
            'service' => $service,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function update($service, $request)
    {
        $service->update($request->all());

        return $service;
    }

    public function create()
    {
        $data = [
            'title' => 'Create new service',
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function store($request)
    {
        $service = Service::create($request->all());

        return $service;
    }

    public function destroy($service)
    {
        $service->delete();

        return $service;
    }

}