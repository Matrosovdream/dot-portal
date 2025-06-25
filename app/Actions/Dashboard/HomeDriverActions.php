<?php
namespace App\Actions\Dashboard;

use App\Models\Service;
use App\Repositories\User\UserRepo;

class HomeDriverActions {

    private $userRepo;

    public function __construct()
    { 
        $this->userRepo = new UserRepo;
    }

    public function index()
    {
        $data = [
            'title' => 'My dashboard',
            'user' => $this->userRepo->getById(auth()->user()->id),
            'services' => Service::paginate(10)
        ];

        return $data;
    }

}