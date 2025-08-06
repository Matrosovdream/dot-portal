<?php

namespace App\Contracts\Saferweb;

interface SaferwebInterface
{
    public function retrieveUsdotData(string $usdotNumber): array;

}    
