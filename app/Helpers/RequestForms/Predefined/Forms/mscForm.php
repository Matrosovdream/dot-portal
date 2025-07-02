<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class MscForm extends AbstractForm
{
    protected $formName = 'mcs150';
    protected $formTitle = 'MCS-150';
    protected $formFields = [
        'pin' => [
            'type' => 'text',
            'label' => 'PIN',
            'required' => true,
        ],
        'change_type' => [
            'type' => 'select',
            'label' => 'Change Type',
            'reference' => 'change_type',
            'required' => true,
        ],
        'ein' => [
            'type' => 'text',
            'label' => 'EIN',
            'required' => false,
        ],

        // Business address fields
        'business_address1' => [
            'type' => 'text',
            'label' => 'Business Address 1',
            'required' => false,
        ],
        'business_address2' => [
            'type' => 'text',
            'label' => 'Business Address 2',
            'required' => false,
        ],
        'business_address_city' => [
            'type' => 'text',
            'label' => 'Business Address City',
            'required' => false,
        ],
        'business_address_state_id' => [
            'type' => 'select',
            'label' => 'Business Address State',
            'reference' => 'country_state',
            'required' => false,
        ],
        'business_address_zip' => [
            'type' => 'text',
            'label' => 'Business Address Zip',
            'required' => false,
        ],

        // Mailing address fields
        'mailing_address1' => [
            'type' => 'text',
            'label' => 'Mailing Address 1',
            'required' => false,
        ],
        'mailing_address2' => [
            'type' => 'text',
            'label' => 'Mailing Address 2',
            'required' => false,
        ],
        'mailing_address_city' => [
            'type' => 'text',
            'label' => 'Mailing Address City',
            'required' => false,
        ],
        'mailing_address_state_id' => [
            'type' => 'select',
            'label' => 'Mailing Address State',
            'reference' => 'country_state',
            'required' => false,
        ],
        'mailing_address_zip' => [          
            'type' => 'text',
            'label' => 'Mailing Address Zip',
            'required' => false,
        ],

        // Contant information fields
        'contact_name' => [
            'type' => 'text',
            'label' => 'Contact Name',
            'required' => false,
        ],
        'contact_phone' => [
            'type' => 'text',
            'label' => 'Contact Phone',
            'required' => false,
        ],
        'contact_email' => [
            'type' => 'email',
            'label' => 'Contact Email',
            'required' => false,
        ],

        // Operation information fields
        'operation_type' => [
            'type' => 'select',
            'label' => 'Operation Type',
            'reference' => 'operation_type',
            'required' => false,
        ],
        'cargo_type' => [
            'type' => 'select',
            'label' => 'Cargo Type',
            'reference' => 'cargo_type',
            'required' => false,     
        ],
        'mileage' => [
            'type' => 'text',
            'label' => 'Mileage',
            'required' => false,
        ],
    ];

    public function validateFormData($requestData) {
        
        $errors = parent::validateFormData($requestData);

        if( $requestData['change_type'] === 'change' ) {
            
            $reqFields = [
                'pin',
                'ein',
                'business_address1',
                'business_address_city',
                'business_address_state_id',
                'business_address_zip',
                'mailing_address1',
                'mailing_address_city',
                'mailing_address_state_id',
                'mailing_address_zip',
                'contact_name',
                'contact_phone',
                'contact_email',
                'operation_type',
                'cargo_type',
                'mileage',
            ];

            foreach ($reqFields as $field) {
                if (empty($requestData[$field])) {
                    $errors[$field] = 'The ' . $this->formFields[$field]['label'] . ' field is required.';
                }
            }

        } 

        return $errors;

    }

    public function getReferences() {

        $refs = [
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

        return $this->prepareReferences( $refs );

    }


}