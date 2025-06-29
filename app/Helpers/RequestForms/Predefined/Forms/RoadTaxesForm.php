<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class RoadTaxesForm extends AbstractForm
{
    protected $formName = 'mcs150';

    protected $formTitle = 'MCS-150';

    protected $formDescription = 'MCS-150 is a form used by the Federal Motor Carrier Safety Administration (FMCSA) to collect information from motor carriers about their operations, including the type of cargo they transport, the number of vehicles they operate, and their safety performance.';

    protected $formFields = [
        'operation_type',
        'cargo_type',
        'mileage',
        'contact_email',
    ];

    public function __construct()
    {
        
    }

    /**
     * Get the form data.
     *
     * @return array
     */
    public function getFormData()
    {
        return [
            'formName' => $this->formName,
            'formTitle' => $this->formTitle,
            'formDescription' => $this->formDescription,
            'formFields' => $this->formFields,
        ];
    }

    /**
     * Get the references for the form fields.
     *
     * @return array
     */
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