<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Models\CompanySaferweb;



class CompanySaferwebRepo extends AbstractRepo
{

    protected $countryStateRepo;

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    public function __construct()
    {
        $this->model = new CompanySaferweb();

    }

    public function sync(int $company_id, array $data): ?CompanySaferweb
    {
        $item = $this->model->where('company_id', $company_id)->first();

        if( empty($item) ) {
            $item = new CompanySaferweb();
            $item->company_id = $company_id;
        }

        $item->fill($data);
        $item->save();

        return $item;
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'company_id' => $item->company_id,
            'dot_number' => $item->dot_number,
            'mc_number' => $item->mc_number,
            'legal_name' => $item->legal_name,
            'dba_name' => $item->dba_name,
            'entity_type' => $item->entity_type,
            'physical_address' => $item->physical_address,
            'mailing_address' => $item->mailing_address,
            'latest_update' => $item->latest_update,
            'api_data' => $item->api_data,
            'Model' => $item
        ];

        return $res;
    }

}