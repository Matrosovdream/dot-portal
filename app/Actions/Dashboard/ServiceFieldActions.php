<?php
namespace App\Actions\Dashboard;

use App\References\FormFieldReferences;
use App\Repositories\References\RefFormFieldRepo;

class ServiceFieldActions {

    private $formFieldRepo;
    private $formFieldRef;

    public function __construct()
    {
        $this->formFieldRepo = new RefFormFieldRepo();

        // References
        $this->formFieldRef = new FormFieldReferences();
    }

    public function index()
    {

        $fields = $this->formFieldRepo->getAllByEntity( 
            $entity = 'service', 
            $paginate = 10 
        );

        $data = [
            'title' => 'Service Fields',
            'fields' => $fields
        ];

        return $data;
    }

    public function show($field_id)
    {
        $field = $this->formFieldRepo->getByID($field_id);

        $data = [
            'title' => 'Service details',
            'field' => $field,
            'references' => $this->getReferences()
        ];

        return $data;
    }

    public function update($field_id, $request)
    {
        $data = $this->formFieldRepo->update($field_id, $request);

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
        $data = $this->formFieldRepo->create($request);
        return $data;
    }

    public function destroy($field_id)
    {
        $data = $this->formFieldRepo->delete($field_id);

        return $data;
    }

    protected function getReferences() {

        return [
            'fieldTypes' => $this->formFieldRef->getTypes(),
        ];

    }

}