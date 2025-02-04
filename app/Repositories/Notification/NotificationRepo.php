<?php
namespace App\Repositories\Notification;

use App\Models\Notification;


class NotificationRepo
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

    public function getAll($filter = [], $paginate = 10)
    {
        $items = $this->model->where($filter)->paginate($paginate);
        return $this->mapItems($items);
    }

    public function getUserNotifications($user_id, $paginate = 10)
    {
        $items = $this->model->where(function ($query) use ($user_id) {
            $query->where('user_id_to', $user_id)
                  ->orWhereNull('user_id_to');
        })->paginate($paginate);
        
        return $this->mapItems($items);
    }

    public function getByID($id)
    {
        $item = $this->model->find($id);
        return $this->mapItem($item);
    }

    public function create($data)
    {
        //$data = $this->validate($data);
        $item = $this->model->create($data);
        return $this->mapItem($item);
    }

    public function update($id, $data)
    {
        //$data = $this->validate($data);
        $item = $this->model->find($id);
        $item->update($data);
        return $this->mapItem($item);
    }

    public function delete($id)
    {
        $item = $this->model->find($id);
        $item->delete();
    }

    private function mapItems($items)
    {
        $itemsMapped = $items->getCollection()->transform(function ($item) {
            return $this->mapItem($item);
        });

        return [
            'items' => $itemsMapped,
            'links' => $items->links(),
            'Model' => $items
        ];
    }

    private function mapItem($item)
    {
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