<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GlobalsService;

class SiteGlobalsController extends Controller
{
    
    public function setLanguage( Request $request )
    {
        GlobalsService::setLanguage( $request->input('lang') );
        return redirect()->back();
    }

    public function setCurrency( Request $request )
    {
        GlobalsService::setCurrency( $request->input('currency') );
        return redirect()->back();
    }


}