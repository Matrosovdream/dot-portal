<?php

namespace App\Helpers\TranspGov;

class TranspGovSnapshot {

    protected $api;
    protected $apiRoute = 'company.snapshot';
    protected $paginateDefault = 10000;
    

    public function __construct() {
        
        $this->api = app('App\Mixins\Integrations\TranspGovAPI');

    }

    public function getItemsByDot(array $usdots, int $paginate = 10000)
    {

        $filter = [];
        $filter['dot_number'] = [
            'value' => $usdots,
            'operator' => 'IN'
        ];

        return $this->retrieveItems( $filter, $paginate );

    } 

    public function retrieveItems( array $filter=[], $paginate=10000 ) {

        $this->api->setFilter( $filter );

        $items = $this->api->request( $this->apiRoute );

        dd($items);

    }

}