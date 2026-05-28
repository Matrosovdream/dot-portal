<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Api\V1\Admin\PlanFeeActions;
use App\Http\Controllers\Controller;
use App\Models\PlanFee;
use Illuminate\Http\Request;

class PlanFeeController extends Controller
{
    public function __construct(protected PlanFeeActions $actions) {}

    public function index(Request $request)
    {
        return response()->json($this->actions->index($request));
    }

    public function store(Request $request)
    {
        $data = $request->validate(self::rules());
        return response()->json(['data' => $this->actions->store($data)], 201);
    }

    public function show(PlanFee $fee)
    {
        return response()->json(['data' => $this->actions->show($fee)]);
    }

    public function update(Request $request, PlanFee $fee)
    {
        $data = $request->validate(self::rules(sometimes: true));
        return response()->json(['data' => $this->actions->update($fee, $data)]);
    }

    private static function rules(bool $sometimes = false): array
    {
        $req = $sometimes ? ['sometimes', 'required'] : ['required'];
        return [
            'name'              => array_merge($req, ['string', 'max:191']),
            'price'             => array_merge($req, ['numeric', 'min:0']),
            'discount'          => ['nullable', 'numeric', 'min:0'],
            'short_description' => ['nullable', 'string'],
            'description'       => ['nullable', 'string'],
            'user_role_id'      => ['nullable', 'integer'],
        ];
    }
}
