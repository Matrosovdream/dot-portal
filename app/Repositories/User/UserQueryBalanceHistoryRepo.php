<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\UserQueryBalanceHistory;


class UserQueryBalanceHistoryRepo extends AbstractRepo
{

    public $model;

    protected $fields = [];

    protected $withRelations = [];

    protected $userRepo;
    protected $metaRepo;

    public function __construct()
    {
        $this->model = new UserQueryBalanceHistory();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'query_balance_id' => $item->query_balance_id,
            'user_id' => $item->user_id,
            'user_company_id' => $item->user_company_id,
            'amount' => $item->amount,
            'type' => $item->type,
            'initiator' => $item->initiator,
            'initiator_id' => $item->initiator_id,
            'notes' => $item->notes,
            'Model' => $item
        ];

        return $res;
    }

}