<?php
namespace App\Repositories\Request;

use App\Repositories\AbstractRepo;
use App\Models\RequestHistory;
use App\Repositories\User\UserRepo;
use App\Repositories\References\RefRequestStatusRepo;

class RequestHistoryRepo extends AbstractRepo
{

    private $userRepo;
    private $requestStatusRepo;
    protected $model;

    protected $fields = ['user', 'status'];

    public function __construct()
    {
        $this->model = new RequestHistory();

        $this->userRepo = new UserRepo();
        $this->requestStatusRepo = new RefRequestStatusRepo();
    }

    public function mapItem($item)  
    {
        if (empty($item)) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'request_id' => $item->request_id,
            'user' => $this->userRepo->mapItem( $item->user ),
            'status' => $this->requestStatusRepo->mapItem( $item->status ),
            'comment' => $item->comment,
            'Model' => $item
        ];
        return $res;
    }

}