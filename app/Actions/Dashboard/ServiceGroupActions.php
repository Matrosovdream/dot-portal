<?php
namespace App\Actions\Dashboard;

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
            'groups' => $groups
        ];

        return $data;
    }

    public function show($group_id)
    {
        $group = $this->serviceGroupRepo->getByID($group_id);

        $data = [
            'title' => 'Service group details',
            'group' => $group
        ];

        return $data;
    }

    public function update($group_id, $request)
    {

        // Prepare the request data
        $request['is_active'] = isset($request['is_active']) ? 1 : 0;

        // Check if the slug already exists
        $isset = $this->serviceGroupRepo->getBySlug( $request['slug'] );
        if ( $isset && $isset['id'] != $group_id ) {
            return [
                'error' => true,
                'message' => 'Group with this slug already exists.'
            ];
        }

        $data = $this->serviceGroupRepo->update($group_id, $request);

        return $data;
    }

    public function create()
    {
        $data = [
            'title' => 'Create new group'
        ];

        return $data;
    }

    public function store($request)
    {

        // Prepare the request data
        $request['is_active'] = isset($request['is_active']) ? 1 : 0;

        // Check if the slug already exists
        $isset = $this->serviceGroupRepo->getBySlug( $request['slug'] );
        if ($isset) {
            return [
                'error' => true,
                'message' => 'Group with this slug already exists.'
            ];
        }

        $data = $this->serviceGroupRepo->create($request);
        return $data;
    }

    public function destroy($group_id)
    {
        $data = $this->serviceGroupRepo->delete($group_id);

        return $data;
    }

}