<?php
namespace App\Actions\Dashboard;

use App\Repositories\Subscription\PlanFeeRepo;


class PlanFeesActions {

    private $feeRepo;

    public function __construct()
    {
        $this->feeRepo = new PlanFeeRepo();

    }

    public function index()
    {

        $data = [
            'title' => 'Plan Fees',
            'tasks' => [],
        ];

        return $data;
    }

    public function show( $fee_id )
    {
        $fee = $this->feeRepo->getByID( $fee_id );

        if( !$fee ) {
            abort(404, 'Fee not found');
        }

        return [
            'title' => 'Plan Fee Details #' . $fee['id'],
            'fee' => $fee,
        ];
    }

}