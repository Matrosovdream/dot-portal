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

        $query = $this->model->with($this->withRelations);

        foreach ($filter as $rawKey => $value) {
            preg_match('/^([a-zA-Z0-9_]+)([><!=]{1,2})?$/', $rawKey, $matches);
            $key = $matches[1] ?? $rawKey;
            $operator = $matches[2] ?? '=';

            // Handle explicit CONDITION array
            if (is_array($value) && isset($value['CONDITION'])) {
                $condition = strtoupper($value['CONDITION']);
                $filtered = array_filter($value, fn($v, $k) => $k !== 'CONDITION', ARRAY_FILTER_USE_BOTH);

                switch ($condition) {
                    case 'BETWEEN':
                        if (count($filtered) === 2) {
                            $query->whereBetween($key, array_values($filtered));
                        }
                        break;

                    case 'IN':
                        $query->whereIn($key, array_values($filtered));
                        break;

                    case 'NOT IN':
                        $query->whereNotIn($key, array_values($filtered));
                        break;

                    case 'NULL':
                        $query->whereNull($key);
                        break;

                    case 'NOT NULL':
                        $query->whereNotNull($key);
                        break;

                    default:
                        break; // skip unsupported
                }

                continue;
            }

            // LIKE operator for strings with %
            if (is_string($value) && strpos($value, '%') !== false) {
                $query->where($key, 'LIKE', $value);
            }
            // IN by default for arrays
            elseif (is_array($value)) {
                $query->whereIn($key, array_filter($value));
            }
            // Normal operator
            else {
                $query->where($key, $operator, $value);
            }
        }

        // Sorting
        if (!empty($sorting)) {
            foreach ($sorting as $column => $direction) {
                if (is_array($direction)) {
                    $query->orderBy($column, $direction[0], $direction[1]);
                } else {
                    $query->orderBy($column, $direction);
                }
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // Capture SQL + bindings
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        $compiledSql = vsprintf(str_replace('?', "'%s'", $sql), $bindings);

        $items = $query->paginate($paginate);

        return array_merge(
            $this->mapItems($items),
            ['Query' => [ 'sql' => $compiledSql ]]
        );
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
            return new Collection();

        }
        
        if ( $map && isset($items) ) {
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