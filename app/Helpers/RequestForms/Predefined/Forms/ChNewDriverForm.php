<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class ChNewDriverForm extends AbstractForm
{
    protected $formName = 'clearinghouse_new_driver';
    protected $formTitle = 'Clearing House New Driver / Company Registration';

    protected $formFields = [
        // General
        'driver' => [
            'type' => 'select',
            'label' => 'Driver',
            'reference' => 'drivers',
            'required' => true,
            'multiple' => true,
        ],
        'primary_driver_phone' => [
            'type' => 'phone',
            'label' => 'Primary Driver Phone',
            'required' => true,
        ],
        'primary_driver_email' => [
            'type' => 'email',
            'label' => 'Primary Driver Email',
            'required' => true,
        ]
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