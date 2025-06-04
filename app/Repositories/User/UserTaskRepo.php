<?php
namespace App\Repositories\User;

use App\Repositories\AbstractRepo;
use App\Repositories\User\UserRepo;
use App\Models\UserTask;


class UserTaskRepo extends AbstractRepo
{

    protected $model;

    protected $fields = [];

    protected $withRelations = [];

    protected $userRepo;

    public function __construct()
    {
        $this->model = new UserTask();

        // Relationship repositories
        $this->userRepo = new UserRepo();
    }

    public function mapItem($item)
    {

        if( empty($item) ) {
            return null;
        }

        $res = [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'user' => $this->userRepo->mapItem($item->user),
            'assigned_to' => $item->assigned_to,
            'assignedUser' => $this->userRepo->mapItem($item->userAssigned),
            'title' => $item->title,
            'description' => $item->description,
            'category' => $item->category,
            'subcategory' => $item->subcategory,
            'status' => $item->status,
            'due_date' => $item->due_date,
            'completed_at' => $item->completed_at,
            'priority' => $item->priority,
            'link' => $item->link,
            'entity' => $item->entity,
            'entity_id' => $item->entity_id,
            'Model' => $item
        ];

        return $res;
    }

}