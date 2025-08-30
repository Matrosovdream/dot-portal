<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;
use App\Repositories\Service\ServiceRepo;

class ScheduleDrugAlcForm extends AbstractForm
{
    protected $formName = 'schedule_drug_alc';
    protected $formTitle = 'Schedule Drug/Alcohol Test';
    protected $formFields = [
        'test_type' => [
            'type' => 'select',
            'label' => 'Test Type',
            'reference' => 'test_type',
            'required' => true,
        ],
        'drivers' => [
            'type' => 'select',
            'label' => 'Driver',
            'reference' => 'drivers',
            'required' => true,
            'multiple' => true,
        ],
        'address_city' => [
            'type' => 'text',
            'label' => 'Business Address City',
            'required' => false,
        ],
        'address_state_id' => [
            'type' => 'select',
            'label' => 'Business Address State',
            'reference' => 'country_state',
            'required' => false,
        ],
        'address_zip' => [
            'type' => 'text',
            'label' => 'Business Address Zip',
            'required' => false,
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
            'country_state' => [
                'type' => 'select',
                'label' => 'State',
                'options' => $this->getCountryStates(),
            ],
        ];
        return $this->prepareReferences($fields);

    }

    private function getTestTypes() {
        return [
            ['value' => 'random_drug_screen', 'title' => 'Random Drug Screen (5-panel)'],
            ['value' => 'post_accident', 'title' => 'Post-Accident Drug & Alcohol Test'],
            ['value' => 'return_to_duty', 'title' => 'Return-to-Duty Test'],
            ['value' => 'follow_up', 'title' => 'Follow-Up Tests'],
            ['value' => 'alcohol_breath', 'title' => 'Alcohol Breath Test'],
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