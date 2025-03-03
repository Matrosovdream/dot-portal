<?php
namespace App\Repositories\Service;

use App\Repositories\AbstractRepo;
use App\Models\Service;
use App\Repositories\References\RefServiceGroupRepo;
use App\Repositories\Service\ServiceFieldRepo;


class ServiceRepo extends AbstractRepo
{

    protected $ServiceGroupRepo;
    protected $serviceFieldRepo;

    protected $model;

    protected $fields = ['formFields'];

    public function __construct()
    {
        $this->model = new Service();

        $this->serviceFieldRepo = new ServiceFieldRepo();

        // References
        $this->ServiceGroupRepo = new RefServiceGroupRepo();

    }

    public function getByGroupID($group_id)
    {
        return $this->getAll(['group_id' => $group_id], $paginate = 1000);
    }

    public function beforeCreate( $data ) {

        $data['status_id'] = 1;
        return $data;

    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }
//dd($this->serviceFieldRepo->mapItems( $item->formFields ));
        return [
            'id' => $item->id,
            'name' => $item->name,
            'slug' => $item->slug,
            'description' => $item->description,
            'price' => $item->price,
            'group' => $this->ServiceGroupRepo->mapItem( $item->group ),
            'formFields' => $this->serviceFieldRepo->mapItems( $item->formFields ), 
            'Model' => $item
        ];
    }

}