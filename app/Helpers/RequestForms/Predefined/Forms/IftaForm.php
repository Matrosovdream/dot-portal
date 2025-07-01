<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class IftaForm extends AbstractForm
{
    protected $formName = 'ifta';
    protected $formTitle = 'IFTA';

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

        return $fields;

    }


}