<?php
namespace App\Repositories\Request;

use App\Repositories\AbstractRepo;
use App\Models\Request;
use App\Repositories\Request\RequestFieldValueRepo;
use App\Repositories\Request\RequestPredefinedValueRepo;
use App\Repositories\Request\RequestHistoryRepo;
use App\Repositories\User\UserRepo;
use App\Repositories\Service\ServiceRepo;
use App\Repositories\References\RefRequestStatusRepo;


class RequestRepo extends AbstractRepo
{

    protected $ServiceGroupRepo;
    protected $serviceFieldRepo;
    protected $fieldValueRepo;
    protected $predefinedValueRepo;
    protected $requestStatusRepo;
    protected $requestHistoryRepo;

    protected $userRepo;
    protected $serviceRepo;

    protected $model;

    protected $fields = ['fieldFields'];

    public function __construct()
    {
        $this->model = new Request();

        // Field values
        $this->fieldValueRepo = new RequestFieldValueRepo();
        $this->predefinedValueRepo = new RequestPredefinedValueRepo();

        // History
        $this->requestHistoryRepo = new RequestHistoryRepo();

        // References
        $this->userRepo = new UserRepo();
        $this->serviceRepo = new ServiceRepo();
        $this->requestStatusRepo = new RefRequestStatusRepo();

    }

    public function syncFieldValues($request_id, $values)
    {

        foreach( $values as $field_id=>$value ) {
            $this->syncFieldValue( $request_id, $field_id, $value );
        }

    }

    public function syncFieldValue( $request_id, $field_id, $value ) {

        $request = $this->getById($request_id);

        switch ($request['service']['form_type']) {
            case 'custom':
                $this->fieldValueRepo->syncValue($request_id, $field_id, $value);
            break;
            case 'predefined':
                $this->predefinedValueRepo->syncValue($request_id, $field_id, $value);
            break;
        }
        
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
            'predefinedValues' => $this->predefinedValueRepo->mapItems($item->predefinedValues, $item->id),
            'history' => $this->requestHistoryRepo->mapItems($item->history),
            'is_paid' => $item->is_paid,
            'price' => $item->price,
            'Model' => $item
        ];
    }

}