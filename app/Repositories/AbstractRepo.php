<?php
namespace App\Repositories;

abstract class AbstractRepo {

    protected $model;
    protected $fields = [];
    protected $withRelations = [];

    public function getByID($id)
    {
        $item = $this->model
            ->with($this->withRelations)
            ->find($id);

        return $this->mapItem($item);
    }

    public function getByUserID($user_id)
    {
        $item = $this->model
            ->where('user_id', $user_id)
            ->with($this->withRelations)
            ->first();

        return $this->mapItem($item);
    }

    public function getAll($filter = [], $paginate = 10)
    {
        $items = $this->model->where($filter)->paginate($paginate);
        return $this->mapItems($items);
    }

    public function create($data)
    {

        // Hook for modifying data before creating
        $data = $this->beforeCreate($data);

        $item = $this->model->create($data);
        return $this->mapItem($item);
    }

    public function beforeCreate($data)
    {
        return $data;
    }

    public function update($id, $data)
    {
        $item = $this->model->find($id);
        $item->update($data);
        return $this->mapItem($item);
    }
    

    public function delete($id)
    {
        $item = $this->model->find($id);
        $item->delete();
    }

    public function mapItems($items)
    {

        if( empty($items) ) { return null; }

        $itemsMapped = $items->getCollection()->transform(function ($item) {
            return $this->mapItem($item);
        });

        //dd($itemsMapped);

        return [
            'items' => $itemsMapped,
            'links' => $items->links(),
            'Model' => $items
        ];
    }

    public function mapItem($item)
    {

        if( empty($item) ) { return null; }

        $res = [
            'id' => $item->id,
            'Model' => $item
        ];

        return $res;
    }

}