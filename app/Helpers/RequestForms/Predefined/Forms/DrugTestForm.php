<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;
use App\Repositories\Service\ServiceRepo;

class DrugTestForm extends AbstractForm
{
    protected $formName = 'drug_test';
    protected $formTitle = 'Drug Test';
    protected $formFields = [
        'drivers' => [
            'type' => 'select',
            'label' => 'Driver',
            'reference' => 'drivers',
            'required' => true,
            'multiple' => true,
        ],
        'test_type' => [
            'type' => 'select',
            'label' => 'Test Type',
            'reference' => 'test_type',
            'required' => true,
        ],
    ];

    public function validateFormData($requestData)
    {

        $errors = [];

        if( empty($requestData['drivers']) || !is_array($requestData['drivers']) ) {
            $errors['drivers'] = 'At least one driver must be selected.';
        }
        if( empty($requestData['test_type']) ) {
            $errors['test_type'] = 'Test type is required field.';
        }

        return $errors;
    }

    public function getReferences() {

        $fields = [
            'drivers' => [
                'type' => 'select',
                'label' => 'Driver',
                'options' => $this->getDrivers(
                    $this->prepareDriversFilter()
                ), 
            ],
            'test_type' => [
                'type' => 'select',
                'label' => 'Test Type',
                'options' => $this->getTestTypes()
            ],
        ];
        return $this->prepareReferences($fields);

    }

    private function getTestTypes() {
        return [
            ['value' => 'consortium', 'title' => 'Consortium Pool (shared)'],
            ['value' => 'standalone', 'title' => 'Standalone Pool (company-only)'],
        ];
    }

    private function prepareDriversFilter() {

        $requestData = $this->requestData;

        if( auth()->user()->isAdmin() ) {
            return [];
        }

        // Vehicle filter based on request data or user company
        if( isset( $requestData['id'] ) ) {
            $vehicleFilter = ['company_user_id' => $requestData['user']['id']];
        } else {
            $vehicleFilter = ['company_user_id' => auth()->user()->id ?? null];
        }

        return $vehicleFilter;

    }

    public function processRequestBeforeSave($requestData, $request)
    {

        $serviceRepo = app(ServiceRepo::class);
        $service = $serviceRepo->getById( $requestData['service_id'] );
        
        // Set the price based on the drivers count
        $requestData['price'] = $service['price'] * count($request['drivers'] ?? []);

        return $requestData;    

    }


}