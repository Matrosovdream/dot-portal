<?php
namespace App\Repositories\Request;

use App\Repositories\AbstractRepo;
use App\Models\Request;
use App\Repositories\Request\RequestFieldValueRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\Service\ServiceRepo;
use App\Repositories\References\RefRequestStatusRepo;


class RequestRepo extends AbstractRepo
{

    protected $ServiceGroupRepo;
    protected $serviceFieldRepo;
    protected $fieldValueRepo;
    protected $requestStatusRepo;

    protected $userRepo;
    protected $serviceRepo;

    protected $model;

    protected $fields = ['fieldFields'];

    public function __construct()
    {
        $this->model = new Request();

        // Field values
        $this->fieldValueRepo = new RequestFieldValueRepo();

        // References
        $this->userRepo = new UserRepo();
         $this->serviceRepo = new ServiceRepo();
        $this->requestStatusRepo = new RefRequestStatusRepo();

    }

    public function syncFieldValues($request_id, $values)
    {
        $request = $this->getById($request_id);

        foreach( $values as $field_id=>$value ) {
            $this->syncFieldValue( $request_id, $field_id, $value );
        }

    }

    public function syncFieldValue( $request_id, $field_id, $value ) {
        $this->fieldValueRepo->syncValue($request_id, $field_id, $value);
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'user' => $this->userRepo->mapItem( $item->user ),
            'status' => $this->requestStatusRepo->mapItem( $item->status ),
            'service' => $this->serviceRepo->mapItem( $item->service ),
            'fieldValues' => $this->fieldValueRepo->mapItems($item->fieldValues),
            'Model' => $item
        ];
    }

}