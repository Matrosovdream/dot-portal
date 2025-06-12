<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Actions\Dashboard\SearchActions;

class SearchController extends Controller
{

    private $searchActions;

    public function __construct()
    {
        $this->searchActions = new SearchActions();
    }
    
    public function index()
    {
        return view('dashboard.notifications.user', $this->searchActions->index());
    }

}
