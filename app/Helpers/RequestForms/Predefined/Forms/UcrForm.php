<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class UcrForm extends AbstractForm
{
    protected $formName = 'ucr';

    protected $formTitle = 'UCR';

    protected $formDescription = 'The Unified Carrier Registration (UCR) is a program that requires motor carriers, motor private carriers, freight forwarders, and brokers to register their business and pay an annual fee based on the size of their fleet. This form is used to collect the necessary information for UCR registration.';

    protected $formFields = [ ];

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
            'range_units' => [
                'type' => 'select',
                'label' => 'Range of Units',
                'options' => [
                    ['value' => '0-2', 'title' => '0-2'],
                    ['value' => '3-5', 'title' => '3-5'],
                    ['value' => '6-20', 'title' => '6-20'],
                    ['value' => '21-100', 'title' => '21-100'],
                ],
            ],
            'permit_year' => [
                'type' => 'select',
                'label' => 'Permit Year',
                'options' => array_map(function($year) {
                    return ['value' => $year, 'title' => $year];
                }, $this->getPermitYears(5)),
            ],
        ];

        return $fields;

    }

    private function getPermitYears( $count = 3 ) 
    {
        $years = [];
        for( $i = 0; $i < $count; $i++ ) {
            $years[] = date('Y') + $i;
        }
        return $years;
    }

}