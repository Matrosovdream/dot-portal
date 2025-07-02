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
            'required' => true,
        ],
        'country_state_id' => [
            'type' => 'select',
            'label' => 'State',
            'required' => true,
        ],
        'vehicle_id' => [
            'type' => 'select',
            'label' => 'Vehicle',
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
                'options' => $this->getVehicles(
                    ['company_id' => auth()->user()->company->id ?? null]
                ), 
            ],
        ];

        return $fields;

    }

}