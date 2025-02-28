<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\References\RefServiceGroupRepo;

class RequestUserActions {

    private $serviceGroupRepo;

    public function __construct()
    {
        $this->serviceGroupRepo = new RefServiceGroupRepo();
    }

    public function showGroup( $groupslug )
    {
        dd($groupslug);

    }

    public function show( $groupslug, $serviceslug )
    {
        dd($groupslug, $serviceslug);
    }

    public function update($group_id, $request)
    {
        $data = $this->serviceGroupRepo->update($group_id, $request);

        return $data;
    }

    public function create()
    {
        $data = [
            'title' => 'Create new group',
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function store($request)
    {
        $data = $this->serviceGroupRepo->create($request);
        return $data;
    }

    public function destroy($group_id)
    {
        $data = $this->serviceGroupRepo->delete($group_id);

        return $data;
    }

}