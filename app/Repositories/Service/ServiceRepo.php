<?php
namespace App\Repositories\Driver;

use App\Models\Driver;


class DriverRepo
{

    protected $model;

    protected $fields = [
        'name' => [ 'type' => 'string', 'required' => true ],
        'user_id' => [ 'type' => 'integer', 'required' => true ],
    ];

    public function __construct()
    {
        $this->model = new Driver();
    }

    public function getAll($filter = [], $paginate = 10)
    {
        $items = $this->model->where($filter)->paginate($paginate);
        return $this->mapItems($items);
    }

    public function getUserDrivers($user_id, $paginate = 10)
    {
        $items = $this->model->where(function ($query) use ($user_id) {
            $query->where('user_id', $user_id)
                  ->orWhereNull('user_id');
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
    
        // Add current user ID
        $data['user_id'] = auth()->user()->id;

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
            'name' => $item->name,
            'user_id' => $item->user_id,
            'Model' => $item
        ];
        return $res;
    }

}