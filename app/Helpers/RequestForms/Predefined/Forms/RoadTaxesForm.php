<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class RoadTaxesForm extends AbstractForm
{
    protected $formName = 'road-taxes';
    protected $formTitle = 'Road Taxes';
    protected $formFields = [
        'query_type' => [
            'type' => 'select',
            'label' => 'Query Type',
            'reference' => 'query_type',
            'required' => true,
        ],
        'vehicle_id' => [
            'type' => 'select',
            'label' => 'Vehicle',
            'reference' => 'vehicles',
            'required' => true,
        ],
    ];

    public function getReferences() {

        $fields = [
            'query_type' => [
                'type' => 'select',
                'label' => 'Query Type',
                'options' => [
                    ['value' => 'new', 'title' => 'New Request'],
                    ['value' => 'renewal', 'title' => 'Renewal Request'],
                ],
            ],
            'vehicles' => [
                'type' => 'select',
                'label' => 'Vehicles',
                'options' => $this->getVehicles( $this->prepareVehiclesFilter() ), 
            ],
        ];

        return $this->prepareReferences($fields);

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