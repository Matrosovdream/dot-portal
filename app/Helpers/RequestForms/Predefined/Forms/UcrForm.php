<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class UcrForm extends AbstractForm
{
    protected $formName = 'ucr';
    protected $formTitle = 'UCR';
    protected $formFields = [
        'range_units' => [
            'type' => 'select',
            'label' => 'Range of Units',
            'required' => true,
        ],
        'permit_year' => [
            'type' => 'select',
            'label' => 'Permit Year',
            'required' => true,
        ],
    ];

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