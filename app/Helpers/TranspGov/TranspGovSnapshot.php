<?php

namespace App\Helpers\TranspGov;

class TranspGovSnapshot extends TranspGovAbstract {

    protected $api;
    protected $apiRoute = 'company.snapshot';

    public function getItemsByDot(array $usdots, int $paginate = 10000)
    {

        $filter = [];
        $filter['dot_number'] = [
            'value' => $usdots,
            'operator' => 'IN'
        ];

        return $this->retrieveItems( $filter, $paginate, $groupBy = 'dot_number' );

    } 

}