<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Api\V1\Admin\SubPlanActions;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubPlanController extends Controller
{
    public function __construct(protected SubPlanActions $actions) {}

    public function index(Request $request)
    {
        return response()->json($this->actions->index($request));
    }

    public function store(Request $request)
    {
        $data = $request->validate(self::rules());
        return response()->json(['data' => $this->actions->store($data)], 201);
    }

    public function show(Subscription $plan)
    {
        return response()->json(['data' => $this->actions->show($plan)]);
    }

    public function update(Request $request, Subscription $plan)
    {
        $data = $request->validate(self::rules($plan->id, sometimes: true));
        return response()->json(['data' => $this->actions->update($plan, $data)]);
    }

    public function destroy(Subscription $plan)
    {
        $this->actions->destroy($plan);
        return response()->noContent();
    }

    private static function rules(?int $id = null, bool $sometimes = false): array
    {
        $req = $sometimes ? ['sometimes', 'required'] : ['required'];
        return [
            'name'              => array_merge($req, ['string', 'max:191']),
            'slug'              => array_merge($req, ['string', 'max:191', $id ? Rule::unique('subscriptions', 'slug')->ignore($id) : 'unique:subscriptions,slug']),
            'price'             => ['nullable', 'numeric', 'min:0'],
            'price_per_driver'  => ['nullable', 'numeric', 'min:0'],
            'is_custom_price'   => ['nullable', 'boolean'],
            'drivers_amount_from' => ['nullable', 'integer', 'min:0'],
            'drivers_amount_to' => ['nullable', 'integer', 'min:0'],
            'discount'          => ['nullable', 'numeric', 'min:0'],
            'duration'          => ['nullable', 'string', 'max:60'],
            'short_description' => ['nullable', 'string'],
            'description'       => ['nullable', 'string'],
        ];
    }
}
