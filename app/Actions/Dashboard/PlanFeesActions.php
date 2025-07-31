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
            'title' => 'Initial Fees',
            'fees' => $this->feeRepo->getAll(),
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
            'title' => 'Initial Fee Details #' . $fee['id'],
            'fee' => $fee,
        ];
    }

    public function update($fee_id, $request)
    {
        $fee = $this->feeRepo->getByID($fee_id);

        if (!$fee) {
            abort(404, 'Fee not found');
        }

        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        // Assuming the request contains the necessary fields to update the fee
        $updatedData = $request->only([
            'price'
        ]);
        $this->feeRepo->update($fee_id, $updatedData);

        return [
            'title' => 'Initial Fee Updated #' . $fee['id'],
            'fee' => $this->feeRepo->getByID($fee_id),
        ];
    }

}