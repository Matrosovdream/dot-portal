<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class ChQueryChecksForm extends AbstractForm
{
    protected $formName = 'clearinghouse_query_checks';
    protected $formTitle = 'Clearing House Query Checks';
    protected $formFields = [
        'query_amount' => [
            'type' => 'number',
            'label' => 'Query Amount',
            'required' => true,
        ],
        'drivers' => [
            'type' => 'select',
            'label' => 'Driver',
            'reference' => 'drivers',
            'required' => true,
            'multiple' => true,
        ],
    ];

    public function getReferences() {

        $fields = [
            'drivers' => [
                'type' => 'select',
                'label' => 'Driver',
                'options' => $this->getDrivers(
                    $this->prepareDriversFilter()
                ), 
            ],
        ];

        return $fields;

    }

    private function prepareDriversFilter() {

        $requestData = $this->requestData;

        // Vehicle filter based on request data or user company
        if( isset( $requestData['id'] ) ) {
            $vehicleFilter = ['company_id' => $requestData['user']['id']];
        } else {
            $vehicleFilter = ['company_id' => auth()->user()->id ?? null];
        }

        return $vehicleFilter;

    }

}