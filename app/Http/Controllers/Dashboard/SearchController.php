<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Actions\Dashboard\SearchActions;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    private $searchActions;

    public function __construct()
    {
        $this->searchActions = new SearchActions();
    }
    
    public function globalSearchAjax( Request $request )
    {

        $validated = $request->validate([
            'q' => 'required|string|max:255',
        ]);

        return response()->json($this->searchActions->globalSearchAjax(  $validated ));
    }

}
