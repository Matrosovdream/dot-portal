<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\References\RefServiceGroupRepo;

class ServiceGroupActions {

    private $serviceGroupRepo;

    public function __construct()
    {
        $this->serviceGroupRepo = new RefServiceGroupRepo();
    }

    public function index()
    {

        $groups = $this->serviceGroupRepo->getAll( 
            [],
            $paginate = 10 
        );

        $data = [
            'title' => 'Service groups',
            'groups' => $groups,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function show($group_id)
    {
        $group = $this->serviceGroupRepo->getByID($group_id);

        $data = [
            'title' => 'Service group details',
            'group' => $group,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
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