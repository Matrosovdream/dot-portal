<?php
namespace App\Actions\Dashboard;

use App\Repositories\Service\ServiceRepo;
use App\Repositories\References\RefServiceGroupRepo;
use App\Repositories\References\RefFormFieldRepo;
use App\References\ServiceReferences;

class ServiceAdminActions {

    private $serviceRepo;
    private $serviceGroupRepo;
    private $refFormFieldRepo;
    private $serviceReferences;

    public function __construct()
    {
        $this->serviceRepo = new ServiceRepo();
        $this->serviceGroupRepo = new RefServiceGroupRepo();
        $this->refFormFieldRepo = new RefFormFieldRepo();

        // References
        $this->serviceReferences = new ServiceReferences();
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
            'services' => $services
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
            'formFieldsRef' => $this->refFormFieldRepo->getAll([], $paginate = 10000),
            'predefinedForms' => $this->serviceReferences->getPredefinedForms(),
        ];

        return $data;
    }

    public function update($service_id, $request)
    {
        return $this->serviceRepo->update($service_id, $request);
    }

    public function updateServiceStatus($service_id, $request)
    {
        $data = $this->serviceRepo->update(
            $service_id, 
            ['status_id' => $request->status]
        );
        return $data;
    }

    public function create()
    {
        $data = [
            'title' => 'Create driver',
            'references' => $this->getReferences()
        ];

        return $data;
    }

    public function store($request)
    {
        return $this->serviceRepo->create($request);
    }

    public function delete($service_id)
    {
        return $this->serviceRepo->delete($service_id);
    }

    public function storeField($service_id, $request, $field_id=null)
    {
        return $this->serviceRepo->syncField($service_id, $request, $field_id);
    }

    public function deleteField($service_id, $field_id)
    {
        return $this->serviceRepo->deleteField($service_id, $field_id);
    }

    public function getReferences()
    {
        return [
            'serviceGroups' => $this->serviceGroupRepo->getAll([], $paginate = 10000),
        ];
    }

}