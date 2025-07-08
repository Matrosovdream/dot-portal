<?php

namespace App\Helpers\TranspGov;

class TranspGovCrash extends TranspGovAbstract {

    protected $api;
    protected $apiRoute = 'crash.history';

    public function getItemsByDot(array $usdots, int $paginate = 10000)
    {

        $filter = [];
        $filter['dot_number'] = [
            'value' => $usdots,
            'operator' => 'IN'
        ];

        return $this->retrieveItems( $filter, $paginate, 'dot_number' );

    } 

}