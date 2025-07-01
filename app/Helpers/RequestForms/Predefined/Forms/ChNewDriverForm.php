<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class ChNewDriverForm extends AbstractForm
{
    protected $formName = 'clearinghouse_new_driver';

    protected $formTitle = 'Clearing House New Driver / Company Registration';

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