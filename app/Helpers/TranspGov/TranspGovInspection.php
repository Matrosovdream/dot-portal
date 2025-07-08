<?php

namespace App\Helpers\TranspGov;

class TranspGovInspection extends TranspGovAbstract {

    protected $api;
    protected $apiRoute = 'inspection.history';

    public function getItemsByDot(array $usdots, int $paginate = 10000)
    {

        $filter = [];
        $filter['dot_number'] = [
            'value' => $usdots,
            'operator' => 'IN'
        ];

        return $this->retrieveItems( $filter, $paginate );

    } 

}