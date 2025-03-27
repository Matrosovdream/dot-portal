<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\DocumentActions;


class DocumentsController extends Controller
{

    private $documentActions;

    public function __construct()
    {
        $this->documentActions = new DocumentActions();
    }

    public function index()
    {
        return view(
            'dashboard.documents.index', 
            $this->documentActions->index()
        );
    }

}
