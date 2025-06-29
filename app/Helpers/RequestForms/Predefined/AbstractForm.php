<?php

namespace App\Helpers\RequestForms\Predefined;

use App\Repositories\References\RefCountryStateRepo;

class AbstractForm
{
    

    public function __construct()
    {
        
    }

    protected function getCountryStates()
    {
        $statesRepo = new RefCountryStateRepo();
        $states = $statesRepo->getAll();

        $list = [];
        foreach ($states['items'] as $state) {
            $list[] = [
                'value' => $state['id'],
                'title' => $state['name']
            ];
        }

        return $list;

    }

}