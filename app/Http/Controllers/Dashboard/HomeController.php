<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Actions\Dashboard\HomeAdminActions;
use App\Actions\Dashboard\HomeManagerActions;
use App\Actions\Dashboard\HomeUserActions;

class HomeController extends Controller
{

    private $homeAdminActions;
    private $homeManagerActions;
    private $homeUserActions;

    public function __construct()
    {
        $this->homeAdminActions = new HomeAdminActions();
        $this->homeManagerActions = new HomeManagerActions();
        $this->homeUserActions = new HomeUserActions();
    }

    public function index()
    {

        // User role
        switch (true) {
            case auth()->user()->isAdmin():
                $route = 'dashboard.home.admin';
                $data = $this->homeAdminActions->index();
                break;
            case auth()->user()->isManager():
                $route = 'dashboard.home.admin';
                $data = $this->homeManagerActions->index();
                break;
            case auth()->user()->isUser():
                $route = 'dashboard.home.user';
                $data = $this->homeUserActions->index();
                break;
            default:
                $route = 'dashboard.home.user';
                $data = $this->homeUserActions->index();
                break;
        }

        return view($route, $data);
    }

}
