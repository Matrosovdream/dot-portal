<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Log;

abstract class AbstractRepo
{

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

    public function getBySlug($slug)
    {

        $item = $this->model
            ->where('slug', $slug)
            ->with($this->withRelations)
            ->first();

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

    public function getAll($filter = [], $paginate = 20, array $sorting = [])
    {
        foreach ($filter as $rawKey => $value) {
            // Extract key and optional operator
            preg_match('/^([a-zA-Z0-9_]+)([><!=]{1,2})?$/', $rawKey, $matches);

            $key = $matches[1] ?? $rawKey;
            $operator = $matches[2] ?? '=';

            // LIKE condition
            if (is_string($value) && strpos($value, '%') !== false) {
                $operator = 'LIKE';
                $this->model = $this->model->where($key, $operator, $value);
                continue;
            }

            // Handle arrays
            if (is_array($value)) {
                // Explicit CONDITION (e.g. BETWEEN)
                if (isset($value['CONDITION'])) {
                    $condition = strtoupper($value['CONDITION']);

                    switch ($condition) {
                        case 'BETWEEN':
                            $range = array_values(array_filter($value, fn($v, $k) => $k !== 'CONDITION', ARRAY_FILTER_USE_BOTH));
                            if (count($range) === 2) {
                                $this->model = $this->model->whereBetween($key, [$range[0], $range[1]]);
                            }
                            break;

                        case 'IN':
                            $inValues = array_filter($value, fn($v, $k) => $k !== 'CONDITION', ARRAY_FILTER_USE_BOTH);
                            $this->model = $this->model->whereIn($key, $inValues);
                            break;

                        case 'NOT IN':
                            $notInValues = array_filter($value, fn($v, $k) => $k !== 'CONDITION', ARRAY_FILTER_USE_BOTH);
                            $this->model = $this->model->whereNotIn($key, $notInValues);
                            break;

                        case 'NULL':
                            $this->model = $this->model->whereNull($key);
                            break;

                        case 'NOT NULL':
                            $this->model = $this->model->whereNotNull($key);
                            break;

                        default:
                            // fallback or skip
                            break;
                    }

                    continue; // skip default operator
                }

                // Default array = IN
                $value = array_filter($value); // Remove empty values
                $this->model = $this->model->whereIn($key, $value);
                continue;
            }

            // Default: use extracted operator
            $this->model = $this->model->where($key, $operator, $value);
        }

        // Sorting
        if (count($sorting) > 0) {
            foreach ($sorting as $key => $value) {
                if (is_array($value)) {
                    $this->model = $this->model->orderBy($key, $value[0], $value[1]);
                } else {
                    $this->model = $this->model->orderBy($key, $value);
                }
            }
        } else {
            $this->model = $this->model->orderBy('id', 'desc');
        }

        $items = $this->model->paginate($paginate);

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

    public function modelSearch( $query, $map=true, $paginate = 20 ) {

        // Validate the query because we depend on the Scout package
        try {
            $items = $this->model::search($query)->paginate($paginate);
        } catch (\Exception $e) {
            Log::error('Search error: '.$e->getMessage());

            // Return an empty collection or null based on your preference
            if( $map ) {
                $items = $this->mapItem($items);
            } else {
                return new Collection();
            }

        }
        
        if ($map) {
            return $this->mapItems($items);
        } else {
            return $items;
        }

    }

    public function mapItems($items)
    {

        if (empty($items)) {
            return null;
        }

        if ($items instanceof Collection) {

            $itemsMapped = $items->transform(function ($item) {
                return $this->mapItem($item);
            });

        } else {

            $itemsMapped = $items->getCollection()->transform(function ($item) {
                return $this->mapItem($item);
            });

        }

        return [
            'items' => $itemsMapped,
            //'links' => $items->links(),
            'Model' => $items
        ];
    }

    public function mapItem($item)
    {

        if (empty($item)) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'Model' => $item
        ];

        return $res;
    }

}