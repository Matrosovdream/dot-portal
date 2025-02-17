<?php
namespace App\Repositories\Notification;

use App\Repositories\AbstractRepo;
use App\Models\Notification;


class NotificationRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [
        'title' => [ 'type' => 'string', 'required' => true ],
        'message' => ['type' => 'text', 'required' => true ],
        'type' => [ 'type' => 'string', 'required' => true ],
        'status' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
        'user_id_to' => [ 'type' => 'integer', 'required' => true ],
    ];

    public function __construct()
    {
        $this->model = new Notification();
    }

    public function mapItem($item)
    {
        if (!$item) return null;
        
        $res = [
            'id' => $item->id,
            'title' => $item->title,
            'message' => $item->message,
            'type' => $item->type,
            'status' => $item->status,
            'user_id' => $item->user_id,
            'user_id_to' => $item->user_id_to,
            'Model' => $item
        ];
        return $res;
    }

}