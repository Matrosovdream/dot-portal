<?php

namespace App\Http\Controllers\Api\V1\Subscriptions;

use App\Actions\Api\V1\Subscriptions\SubscriptionActions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(protected SubscriptionActions $actions) {}

    public function show(Request $request)
    {
        return response()->json(['data' => $this->actions->show($request->user())]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'subscription_id' => ['required', 'integer', 'exists:subscriptions,id'],
            'drivers_number'  => ['nullable', 'integer', 'min:0'],
            'payment_card_id' => ['nullable', 'integer'],
        ]);
        return response()->json(['data' => $this->actions->update($request->user(), $data)]);
    }

    public function cancel(Request $request)
    {
        $sub = $this->actions->cancel($request->user());

        if (!$sub) {
            return response()->json(['message' => 'No active subscription to cancel.'], 404);
        }

        return response()->json(['data' => $sub]);
    }
}
