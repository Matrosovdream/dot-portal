<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\ProfileUserActions;


class DashboardProfileController {

    private $userProfileActions;

    public function __construct()
    {
        $this->userProfileActions = new ProfileUserActions();
    }

    public function profilePreview()
    {
        return view(
            'dashboard.profile.show', 
            $this->userProfileActions->profilePreview()
            );
    }

    public function profileEdit()
    {
        return view(
            'dashboard.profile.show', 
            $this->userProfileActions->profileEdit()
            );
    }

    public function profileUpdate(Request $request)
    {
        dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'password' => 'nullable',
            'password_confirmation' => 'nullable',
        ]);

        $data = $this->userProfileActions->profileUpdate($request);
        
        return redirect()->route('dashboard.profile.index');
    }

    public function companyEdit()
    {
        return view(
            'dashboard.profile.show', 
            $this->userProfileActions->companyEdit()
            );
    }

    public function companyUpdate(Request $request)
    {
        dd($request->all());    
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'password' => 'nullable',
            'password_confirmation' => 'nullable',
        ]);

        $data = $this->userProfileActions->profileUpdate($request);
        
        return redirect()->route('dashboard.profile.index');
    }

}