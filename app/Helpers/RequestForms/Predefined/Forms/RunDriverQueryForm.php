<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class RunDriverQueryForm extends AbstractForm
{
    protected $formName = 'run_driver_query';
    protected $formTitle = 'Run Driver Query';

    protected $formFields = [
        // General
        'driver' => [
            'type' => 'select',
            'label' => 'Driver',
            'reference' => 'drivers',
            'required' => true,
            'multiple' => true,
        ],
        'query_type' => [
            'type' => 'select',
            'label' => 'Query Type',
            'reference' => 'query_type',
            'required' => true,
            'multiple' => false,
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
            'query_type' => [
                'type' => 'select',
                'label' => 'Query Type',
                'options' => $this->getQueryTypeOptions()
            ],
        ];

        return $this->prepareReferences($fields);

    }

    private function getQueryTypeOptions() {
        return [
            ['value' => 'limited', 'title' => 'Limited'],
            ['value' => 'full', 'title' => 'Full'],
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