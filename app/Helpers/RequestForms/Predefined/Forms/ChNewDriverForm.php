<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class ChNewDriverForm extends AbstractForm
{
    protected $formName = 'clearinghouse_new_driver';
    protected $formTitle = 'Clearing House New Driver / Company Registration';

    protected $formFields = [
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