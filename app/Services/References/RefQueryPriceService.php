<?php

namespace App\Services\References;

use App\Repositories\References\RefQueryPriceRepo;

class RefQueryPriceService
{
    
    public function __construct(
        protected RefQueryPriceRepo $refQueryPriceRepo
    )
    {
        
    }

    public function getByType( $type ): array
    {
        return $this->refQueryPriceRepo->getByType( $type );
    }

    public function getByUserId( $user_id, $type='chm' ): array
    {
        
        $priceItem = $this->refQueryPriceRepo->getByType( $type );
        $rules = $priceItem['rules'] ?? [];

        return $rules;

    }

}