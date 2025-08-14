<?php
namespace App\Actions\Dashboard;

use App\Repositories\User\UserSubscriptionRepo;
use App\Repositories\Subscription\SubscriptionRepo;


class SubManagerActions {

    private $subRepo;
    private $subListRepo;

    public function __construct()
    {
        $this->subRepo = new UserSubscriptionRepo();
        $this->subListRepo = new SubscriptionRepo();

    }

    public function index()
    {

        $this->subRepo->setRelations(['user', 'subscription']);
        $subs = $this->subRepo->getAll();

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
        ];
    }

    public function store($request)
    {

        $data = $request->only([
            'custom_price',
        ]);

        $req = $this->subRepo->create($data);

        if( !$req ) {
            abort(500, 'Failed to create request');
        }

        return [
            'title' => 'Request Created Successfully',
            'sub_id' => $req['id'],
        ];
    }

    public function show( $sub_id )
    {
        $this->subRepo->setRelations(['user', 'subscription']);
        $sub = $this->subRepo->getByID( $sub_id );

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
        $req = $this->subRepo->getByID($sub_id);

        if (!$req) {
            abort(404, 'Request not found');
        }

    }

    public function sendEmail($sub_id)
    {
        $req = $this->subRepo->getByID($sub_id);

        if (!$req) {
            abort(404, 'Request not found');
        }

        $userService = app('App\Services\User\UserService');

        // Send email to the user
        $user = $req['user']['Model'];
        $userService->sendApprovedRequestEmail( $user );

        // Assuming the email was sent successfully
        return true; // Or return some response indicating success
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