<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Api\V1\Admin\SubManagerActions;
use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class SubManagerController extends Controller
{
    public function __construct(protected SubManagerActions $actions) {}

    public function index(Request $request)
    {
        return response()->json($this->actions->index($request));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'         => ['required', 'integer', 'exists:users,id'],
            'subscription_id' => ['required', 'integer', 'exists:subscriptions,id'],
            'drivers_number'  => ['nullable', 'integer', 'min:0'],
            'price_per_driver'=> ['nullable', 'numeric', 'min:0'],
            'discount'        => ['nullable', 'numeric', 'min:0'],
            'status'          => ['nullable', 'string', 'max:30'],
        ]);
        $rec = $this->actions->store($data);
        return response()->json(['data' => $rec->load('subscription')], 201);
    }

    public function show(UserSubscription $userSubscription)
    {
        return response()->json(['data' => $this->actions->show($userSubscription)]);
    }

    public function update(Request $request, UserSubscription $userSubscription)
    {
        $data = $request->validate([
            'subscription_id' => ['sometimes', 'integer', 'exists:subscriptions,id'],
            'drivers_number'  => ['nullable', 'integer', 'min:0'],
            'price_per_driver'=> ['nullable', 'numeric', 'min:0'],
            'discount'        => ['nullable', 'numeric', 'min:0'],
            'status'          => ['nullable', 'string', 'max:30'],
        ]);
        return response()->json(['data' => $this->actions->update($userSubscription, $data)]);
    }

    public function destroy(UserSubscription $userSubscription)
    {
        $this->actions->destroy($userSubscription);
        return response()->noContent();
    }

    public function sendOnetime(UserSubscription $userSubscription)
    {
        $this->actions->sendOnetime($userSubscription);
        return response()->json(['message' => 'One-time login link queued.']);
    }

    public function sendPaymentLink(UserSubscription $userSubscription)
    {
        $this->actions->sendPaymentLink($userSubscription);
        return response()->json(['message' => 'Payment link queued.']);
    }
}
