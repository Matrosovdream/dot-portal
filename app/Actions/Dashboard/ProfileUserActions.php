<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\User\UserRepo;
use App\Repositories\References\RefCountryStateRepo;


class ProfileUserActions {

    private $userRepo;
    private $refStateRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepo();

        // References
        $this->refStateRepo = new RefCountryStateRepo();

    }

    public function profilePreview()
    {
        $user = $this->userRepo->getByID( auth()->user()->id );

        $data = [
            'title' => 'Profile details',
            'user' => $user,
            'references' => $this->getReferences(),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];
//dd($data);
        return $data;
    }

    public function profileEdit()
    {
        $data = $this->profilePreview();
        $data['title'] = 'Edit profile details';
        return $data;
    }

    public function profileUpdate($driver_id, $request)
    {
        $data = $this->driverRepo->update($driver_id, $request);

        return $data;
    }

    public function companyEdit()
    {
        $data = $this->profilePreview();
        $data['title'] = 'Edit company details';
        return $data;
    }

    public function companyUpdate($company_id, $request)
    {
        dd($request->all());
        $data = $this->driverRepo->update($driver_id, $request);

        return $data;
    }




    public function getReferences()
    {
        $references = [
            'state' => $this->refStateRepo->getAll([], $paginate=100),
        ];

        return $references;
    }

}