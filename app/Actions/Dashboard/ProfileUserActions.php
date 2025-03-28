<?php
namespace App\Actions\Dashboard;

use App\Repositories\User\UserRepo;
use App\Repositories\User\UserAddressRepo;
use App\Repositories\User\UserCompanyRepo;
use App\Repositories\User\UserCompanyAddressRepo;
use App\Repositories\References\RefCountryStateRepo;


class ProfileUserActions {

    private $userRepo;
    private $userAddressRepo;
    private $userCompanyRepo;
    private $userCompanyAddressRepo;
    private $refStateRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepo();
        $this->userAddressRepo = new UserAddressRepo();
        $this->userCompanyRepo = new UserCompanyRepo();
        $this->userCompanyAddressRepo = new UserCompanyAddressRepo();

        // References
        $this->refStateRepo = new RefCountryStateRepo();

    }

    public function profilePreview()
    {
        $user = $this->userRepo->getByID( auth()->user()->id );

        $data = [
            'title' => 'Profile details',
            'user' => $user,
            'references' => $this->getReferences()
        ];

        return $data;
    }

    public function profileEdit()
    {
        $data = $this->profilePreview();
        $data['title'] = 'Edit profile details';
        return $data;
    }

    public function profileUpdate($request)
    {
        return $this->userRepo->update( auth()->user()->id, $request);
    }

    public function profileAddressUpdate($request)
    {
        $user = $this->userRepo->getByID( auth()->user()->id );
        if( !$user['address'] ) {
            // Set user ID
            $request['user_id'] = auth()->user()->id;

            $this->userAddressRepo->create( $request);
        } else {
            $this->userAddressRepo->update( $user['address']['id'], $request);
        }

        return redirect()->route('dashboard.profile.edit');
    }

    public function companyEdit()
    {
        $data = $this->profilePreview();
        $data['title'] = 'Edit company details';
        return $data;
    }

    public function companyUpdate($request)
    {

        $user = $this->userRepo->getByID( auth()->user()->id );
        if( !$user['company'] ) {
            // Set user ID
            $request['user_id'] = $user['id'];

            $this->userCompanyRepo->create( $request);
        } else {
            $this->userCompanyRepo->update( $user['company']['id'], $request);
        }

        // Refresh data
        $user = $this->userRepo->getByID( auth()->user()->id );
        $addresses = $user['company']['addresses'] ?? null;

        if( isset($user['company']) ) {

            // Save company address
            $addressTypes = ['business', 'mailing'];

            foreach( $addressTypes as $type ) {

                $this->updateCompanyAddress( 
                    $user['company']['id'], 
                    array_merge($request[$type], ['type' => $type]),
                    $addresses[$type]['id'] ?? null
                );

            }

        }

        return redirect()->route('dashboard.profile.company');
    }

    private function updateCompanyAddress( $companyId, $data, $address_id = null ) {

        if( !isset($address_id) ) {
            $data['item_id'] = $companyId;
            $this->userCompanyAddressRepo->create($data);
        } else {
            $this->userCompanyAddressRepo->update($address_id, $data);
        }

    }


    public function getReferences()
    {
        $references = [
            'state' => $this->refStateRepo->getAll([], $paginate=100),
        ];

        return $references;
    }

}