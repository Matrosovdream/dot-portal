<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class IftaForm extends AbstractForm
{
    protected $formName = 'ifta';
    protected $formTitle = 'IFTA';
    protected $formFields = [
        'request_type' => [
            'type' => 'select',
            'label' => 'Request Type',
            'reference' => 'request_type',
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
        'filing_period' => [
            'type' => 'select',
            'label' => 'Filing Period',
            'reference' => 'filing_period',
        ],
    ];


    public function validateFormData($requestData)
    {

        $errors = [];

        if( $requestData['request_type'] == 'reg' ) {
            if( empty($requestData['country_state_id']) ) {
                $errors['country_state'] = 'State is required field.';
            }
            if( empty($requestData['vehicle_id']) ) {
                $errors['vehicle'] = 'Vehicle is required field.';
            }
        } elseif( $requestData['request_type'] == 'quarterly_filing' ) {
            if( empty($requestData['filing_period']) ) {
                $errors['filing_period'] = 'Period is required field.';
            }
            if( empty($requestData['vehicle_id']) ) {
                $errors['vehicle'] = 'Vehicle is required field.';
            }
        }

        return $errors;
    }

    public function getReferences() {

        $fields = [
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
            'filing_period' => [
                'type' => 'select',
                'label' => 'Filing Period',
                'options' => $this->getQuarterPeriods(),
            ],
        ];

        return $this->prepareReferences($fields);

    }


}