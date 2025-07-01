<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class MscForm extends AbstractForm
{
    protected $formName = 'mcs150';
    protected $formTitle = 'MCS-150';

    public function getReferences() {

        $fields = [
            'change_type' => [
                'type' => 'select',
                'label' => 'Change Type',
                'options' => [
                    ['value' => 'keep', 'title' => 'Keep same'],
                    ['value' => 'change', 'title' => 'Change information'],
                ],
            ],
            'operation_type' => [
                'type' => 'select',
                'label' => 'Operation Type',
                'options' => [
                    ['value' => 'interstate', 'title' => 'Interstate'],
                    ['value' => 'intrastate', 'title' => 'Intrastate'],
                ],
            ],
            'cargo_type' => [
                'type' => 'select',
                'label' => 'Cargo Type',
                'options' => [
                    ['value' => 'general', 'title' => 'General Freight'],
                    ['value' => 'hazardous', 'title' => 'Hazardous Materials'],
                    ['value' => 'household', 'title' => 'Household Goods'],
                    ['value' => 'passenger', 'title' => 'Passenger'],
                ],
            ],
            'country_state' => [
                'type' => 'select',
                'label' => 'State',
                'options' => $this->getCountryStates(),
            ],
        ];

        return $fields;

    }


}