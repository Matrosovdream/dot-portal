<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class IrpForm extends AbstractForm
{
    protected $formName = 'IRP';
    protected $formTitle = 'irp';

    protected $formFields = [
        'change_type' => [
            'type' => 'select',
            'label' => 'Change Type',
            'reference' => 'change_type',
            'required' => true,
        ],
        'country_state_id' => [
            'type' => 'select',
            'label' => 'State',
            'reference' => 'country_state',
            'required' => true,
        ],
        'vehicle_id' => [
            'type' => 'select',
            'label' => 'Vehicle',
            'reference' => 'vehicles',
            'required' => true,
        ],
    ];

    public function validateFormData($requestData)
    {

        $errors = [];

        if( $requestData['change_type'] == 'new' ) {
            if( empty($requestData['country_state_id']) ) {
                $errors['country_state'] = 'State is required field.';
            }
            if( empty($requestData['vehicle_id']) ) {
                $errors['vehicle'] = 'Vehicle is required field.';
            }
        } elseif( $requestData['change_type'] == 'renewal' ) {
            if( empty($requestData['vehicle_id']) ) {
                $errors['vehicle'] = 'Vehicle is required field.';
            }
        }

        return $errors;
    }

    public function getReferences() {

        $fields = [
            'change_type' => [
                'type' => 'select',
                'label' => 'Change Type',
                'options' => [
                    ['value' => 'new', 'title' => 'New'],
                    ['value' => 'renewal', 'title' => 'Renewal'],
                ],
            ],
            'country_state' => [
                'type' => 'select',
                'label' => 'State',
                'options' => $this->getCountryStates(),
            ],
            'vehicles' => [
                'type' => 'select',
                'label' => 'Vehicles',
                'options' => $this->getVehicles( $this->prepareVehiclesFilter() ),  
            ],
        ];

        return $this->prepareReferences( $fields );

    }

    private function prepareVehiclesFilter() {

        $requestData = $this->requestData;

        // Vehicle filter based on request data or user company
        if( isset( $requestData['id'] ) ) {
            $vehicleFilter = ['company_id' => $requestData['user']['company']['id']];
        } else {
            $vehicleFilter = ['company_id' => auth()->user()->company->id ?? null];
        }

        return $vehicleFilter;

    }

}