<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class ChNewDriverForm extends AbstractForm
{
    protected $formName = 'clearinghouse_new_driver';
    protected $formTitle = 'Clearing House New Driver / Company Registration';

    public function getReferences() {

        $fields = [
            'drivers' => [
                'type' => 'select',
                'label' => 'Driver',
                'options' => $this->getDrivers(
                    ['company_id' => auth()->user()->company->id ?? null]
                ), 
            ],
        ];

        return $fields;

    }


}