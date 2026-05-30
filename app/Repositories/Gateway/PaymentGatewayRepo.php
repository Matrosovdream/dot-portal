<?php

namespace App\Repositories\Gateway;

use App\Models\PaymentGateway;
use App\Repositories\AbstractRepo;

class PaymentGatewayRepo extends AbstractRepo
{
    public function __construct()
    {
        $this->model = new PaymentGateway();
    }
}
