<?php
namespace App\References;

class ServiceReferences {

    public function getPredefinedForms() {

        return [
            1 => [
                'id' => 1,
                'name' => 'MCS-150',
                'path' => 'forms.request.mcs150',
                'classProcess' => 'App\Helpers\RequestForms\Predefined\Forms\MscForm',
                'fields' => []
            ],
            2 => [
                'id' => 2,
                'name' => 'UCR',
                'path' => 'forms.request.ucr',
                'classProcess' => 'App\Helpers\RequestForms\Predefined\Forms\UcrForm',
                'fields' => [
                    'range_of_units' => ['title' => 'Range of units', 'type' => 'text', 'required' => true],
                    'date_of_birth' => ['title' => 'Date of birth', 'type' => 'date', 'required' => true],
                    'permit_year' => ['title' => 'Permit year', 'type' => 'text', 'required' => true],
                ]
            ],
            3 => [
                'id' => 3,
                'name' => 'Road Taxes',
                'path' => 'forms.request.road-taxes',
                'classProcess' => 'App\Helpers\RequestForms\Predefined\Forms\RoadTaxesForm',
                'fields' => []
            ],
            4 => [
                'id' => 4,
                'name' => 'IFTA',
                'path' => 'forms.request.ifta',
                'classProcess' => 'App\Helpers\RequestForms\Predefined\Forms\IftaForm',
                'fields' => []
            ],
            5 => [
                'id' => 5,
                'name' => 'IRP',
                'path' => 'forms.request.irp',
                'classProcess' => 'App\Helpers\RequestForms\Predefined\Forms\IrpForm',
                'fields' => []
            ],
            6 => [
                'id' => 6,
                'name' => 'Clearing House New Driver',
                'path' => 'forms.request.ch-new-driver',
                'classProcess' => 'App\Helpers\RequestForms\Predefined\Forms\ChNewDriverForm',
                'fields' => []
            ],
            7 => [
                'id' => 7,
                'name' => 'Clearing House Query Checks',
                'path' => 'forms.request.ch-query-checks',
                'classProcess' => 'App\Helpers\RequestForms\Predefined\Forms\ChQueryChecksForm',
                'fields' => []
            ],
            8 => [
                'id' => 7,
                'name' => 'Randomized Drug Testing Program',
                'path' => 'forms.request.drug-test',
                'classProcess' => 'App\Helpers\RequestForms\Predefined\Forms\DrugTestForm',
                'fields' => []
            ],
            9 => [
                'id' => 8,
                'name' => 'Schedule Drug/Alcohol Test',
                'path' => 'forms.request.schedule-drug-alc',
                'classProcess' => 'App\Helpers\RequestForms\Predefined\Forms\ScheduleDrugAlcForm',
                'fields' => []
            ],
            
        ];
        
    }

    public static function serviceFields() {

        return [
            [
                'title' => "Your full name",
                'slug' => "full_name",
                'entity' => "service",
                'type' => "text",
                'placeholder' => "",
                'tooltip' => "",
                'description' => "",
                'default_value' => "",
                'reference_code' => "",
                'default' => true,
                'section' => "trip",
                'icon' => 'c_user.svg',
            ],
            [
                'title' => "Phone number",
                'slug' => "phone_number",
                'entity' => "service",
                'type' => "phone",
                'placeholder' => "",
                'tooltip' => "",
                'description' => "",
                'default_value' => "",
                'reference_code' => "",
                'default' => true,
                'section' => "trip",
                'icon' => 'c_call.svg',
            ],
            [
                'title' => "Email address",
                'slug' => "email",
                'entity' => "service",
                'type' => "email",
                'placeholder' => "",
                'tooltip' => "We use this to create your account and send you updates about your application.",
                'description' => "",
                'default_value' => "",
                'reference_code' => "",
                'default' => true,
                'section' => "trip",
                'icon' => 'c_mail.svg',
            ],
            [
                'title' => "Document",
                'slug' => "document",
                'entity' => "service",
                'type' => "file",
                'placeholder' => "",
                'tooltip' => "",
                'description' => "",
                'default_value' => "",
                'reference_code' => "",
                'default' => true,
                'section' => "",
                'icon' => '',
            ],
        ];

    }

    public function getFormFields($form_id=null) {

        if( empty($form_id) ) {
            return [];
        }

        $forms = $this->getPredefinedForms();

        if( isset($forms[$form_id]) ) {
            return $forms[$form_id]['fields'] ?? [];
        }

        return [];

    }

}