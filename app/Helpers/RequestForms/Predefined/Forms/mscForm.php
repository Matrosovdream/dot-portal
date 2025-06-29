<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class MscForm extends AbstractForm
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



}