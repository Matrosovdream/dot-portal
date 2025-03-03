<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\References\RefServiceGroupRepo;
use App\Repositories\Service\ServiceRepo;

class RequestUserActions {

    private $serviceGroupRepo;
    private $serviceRepo;

    public function __construct()
    {
        $this->serviceGroupRepo = new RefServiceGroupRepo();
        $this->serviceRepo = new ServiceRepo();
    }

    public function showGroup( $groupslug )
    {
        $group = $this->serviceGroupRepo->getBySlug($groupslug);
        if( $group ) {
            $services = $this->serviceRepo->getByGroupID($group['id']);
        }
        
        return [
            'title' => 'Services of ' . $groupslug,
            'group' => $group,
            'services' => $services,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

    }

    public function show( $groupslug, $serviceslug )
    {
        // Group
        $group = $this->serviceGroupRepo->getBySlug($groupslug);
        if( $group ) {
            $services = $this->serviceRepo->getByGroupID($group['id']);
        }

        // Service
        $service = $this->serviceRepo->getBySlug($serviceslug);
        
        return [
            'title' => 'Services of ' . $groupslug,
            'group' => $group,
            'service' => $service,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];
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