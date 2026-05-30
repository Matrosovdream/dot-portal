<?php

namespace App\Actions\Api\V1\Admin;

use App\Repositories\Gateway\PaymentGatewayRepo;
use Illuminate\Http\Request;

class GatewayActions
{
    public function __construct(protected PaymentGatewayRepo $repo) {}

    public function index(Request $request)
    {
        $q = $this->repo->getModel()::query();

        if ($search = $request->query('q')) {
            $q->where('name', 'like', '%'.$search.'%');
        }

        $perPage = min(100, (int) $request->query('per_page', 25));

        return $q->paginate($perPage);
    }
}
