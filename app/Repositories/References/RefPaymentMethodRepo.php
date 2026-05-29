<?php
namespace App\Repositories\References;

use App\Repositories\AbstractRepo;
use App\Models\RefPaymentMethod;


class RefPaymentMethodRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    public function __construct()
    {
        $this->model = new RefPaymentMethod();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'name' => $item->name,
            'code' => $item->code,
            'description' => $item->description,
        ];
        return $res;
    }

}