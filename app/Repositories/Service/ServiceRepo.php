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
        $this->serviceFieldRepo = new ServiceFieldRepo();

    }

    public function getByGroupID($group_id)
    {
        return $this->getAll(['group_id' => $group_id], $paginate = 1000);
    }

    public function syncField($service_id, $data, $field_id=null)
    {

        if( !$service_id ) { return null; }
        $service = $this->getByID($service_id);

        if( $field_id ) {
            $service['Model']->formFields()->where('id', $field_id)->update($data);
        } else {
            $service['Model']->formFields()->create($data);
        }

    }

    public function deleteField($service_id, $field_id)
    {
        if( !$service_id ) { return null; }
        $service = $this->getByID($service_id);

        $service['Model']->formFields()->where('id', $field_id)->delete();
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

        return [
            'id' => $item->id,
            'name' => $item->name,
            'slug' => $item->slug,
            'description' => $item->description,
            'price' => $item->price,
            'group' => $this->ServiceGroupRepo->mapItem( $item->group ),
            'formFields' => $this->serviceFieldRepo->mapItems($item->formFields->sortBy('order')),
            'Model' => $item
        ];
    }

}