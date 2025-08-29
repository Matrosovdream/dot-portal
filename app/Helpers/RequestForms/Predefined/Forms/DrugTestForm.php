<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

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

        /*
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
        */

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


}