<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class ChNewCompanyForm extends AbstractForm
{
    protected $formName = 'ch_company_register';
    protected $formTitle = 'Clearing House Company Registration';

    protected $formFields = [
        'company_name' => [
            'type' => 'text',
            'label' => 'Company Name',
            'placeholder' => 'Enter company name',
            'required' => true,
        ],
        'usdot' => [
            'type' => 'text',
            'label' => 'USDOT Number',
            'placeholder' => 'Enter USDOT number',
            'required' => true,
        ],
        'primary_contact_name' => [
            'type' => 'text',
            'label' => 'Primary Contact Name',
            'placeholder' => 'Enter primary contact name',
            'required' => true,
        ],
        'primary_contact_email' => [
            'type' => 'email',  
            'label' => 'Primary Contact Email',
            'placeholder' => 'Enter primary contact email',
            'required' => true,
        ],
        'primary_contact_phone' => [
            'type' => 'phone',
            'label' => 'Primary Contact Phone',
            'placeholder' => 'Enter primary contact phone',
            'required' => true,
        ],
    ];

}