<?php
namespace App\Actions\Dashboard;

use App\Repositories\User\UserRepo;
use App\Repositories\User\UserSubscriptionRepo;
use App\Repositories\Subscription\SubscriptionRepo;
use App\Repositories\User\UserCompanyRepo;
use App\Services\User\UserService;


class SubManagerActions {

    public function __construct(
        protected UserService $userService,
        protected UserRepo $userRepo,
        protected UserSubscriptionRepo $userSubRepo,
        protected UserCompanyRepo $userCompanyRepo,
        protected SubscriptionRepo $subListRepo,
    )
    {}

    public function index()
    {

        $this->userSubRepo->setRelations(['user', 'subscription']);
        $subs = $this->userSubRepo->getAll();

        return [
            'title' => 'User Subscriptions',
            'subs' => $subs,
            'statuses' => $this->getStatuses(),
        ];
    }

    public function create()
    {
        return [
            'title' => 'Create New Subscription',
            'subList' => $this->subListRepo->getAll()
        ];
    }

    public function store($request)
    {

        // Create a new user and assign role
        $user = $this->userRepo->create($request->user);
        $user['Model']->setRole('company');

        if( $user ) {

            // Sync user company
            $userCompany = $this->userCompanyRepo->syncItem($user['id'], $request->company);

            // Sync subscription details
            $userSub = $this->userSubRepo->create(
                $request->sub + [
                    'user_id' => $user['id'],
                    'status' => 'disabled', // Default status
                ]
            );

            if( $userCompany && $userSub ) {
                return [
                    'error' => false,
                    'user' => $user,
                    'sub' => $userSub,
                ];
            } 

            //dd($user, $userCompany, $userSub, $request->all());

        } else {
            return [
                'error' => true,
                'message' => 'User creation failed, please try again.',
            ];
        }

        return true;
    }

    public function show( $sub_id )
    {
        $this->userSubRepo->setRelations(['user', 'subscription']);
        $sub = $this->userSubRepo->getByID( $sub_id );

        if( !$sub ) {
            abort(404, 'Subscription not found');
        }
        $data =  [
            'title' => 'Subscription Details #' . $sub['id'],
            'statuses' => $this->getStatuses(),
            'sub' => $sub,
            'user' => $sub['user'],
            'company' => $sub['user']['company'],
            'subList' => $this->subListRepo->getAll()
        ];

        if( request()->has('debug') ) {
            dd($data);
        }

        return $data;
    }

    public function update($sub_id, $request)
    {

        $sub = $this->getSub( $sub_id );
        $sub['Model']->update( $request );
        return true;
    }

    public function userStore($sub_id, $request)
    {
        $sub = $this->getSub( $sub_id );
        $sub['user']['Model']->update( $request );
        return true;
    }

    public function companyStore($sub_id, $request)
    {
        $sub = $this->getSub( $sub_id );
        $sub['user']['company']['Model']->update( $request );
        return true;
    }

    public function sendOnceLogin($sub_id)
    {
        $sub = $this->getSub( $sub_id );

        // Send one-time login link to the user
        $this->userService->sendOnceLoginLink($sub['user']['Model']);

        // Assuming the email was sent successfully
        return true; 
    }

    private function getSub($sub_id)
    {
        $this->userSubRepo->setRelations(['user', 'subscription']);
        $sub = $this->userSubRepo->getByID($sub_id);

        if (!$sub) {
            abort(404, 'Subscription not found');
        }

        return $sub;
    }

    private function getStatuses() {

        return [
            '1' => 'Pending',
            '2' => 'Approved',
            '3' => 'Rejected',
            '4' => 'Completed',
        ];

    }

}