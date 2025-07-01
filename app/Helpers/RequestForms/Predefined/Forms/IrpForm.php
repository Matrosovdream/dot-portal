<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class IrpForm extends AbstractForm
{
    protected $formName = 'IRP';

    protected $formTitle = 'irp';

    protected $formDescription = '';

    protected $formFields = [];

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
        ];

        return $fields;

    }

}