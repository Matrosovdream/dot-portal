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
            'required' => true,
        ],
        'vehicle_id' => [
            'type' => 'select',
            'label' => 'Vehicle',
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
                'options' => $this->getVehicles(
                    ['company_id' => auth()->user()->company->id ?? null]
                ), 
            ],
        ];

        return $fields;

    }


}