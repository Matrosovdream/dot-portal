<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Api\V1\Admin\ServiceGroupActions;
use App\Http\Controllers\Controller;
use App\Models\RefServiceGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceGroupController extends Controller
{
    public function __construct(protected ServiceGroupActions $actions) {}

    public function index(Request $request)
    {
        return response()->json($this->actions->index($request));
    }

    public function store(Request $request)
    {
        $data = $request->validate(self::rules());
        return response()->json(['data' => $this->actions->store($data)], 201);
    }

    public function show(RefServiceGroup $group)
    {
        return response()->json(['data' => $this->actions->show($group)]);
    }

    public function update(Request $request, RefServiceGroup $group)
    {
        $data = $request->validate(self::rules($group->id, sometimes: true));
        return response()->json(['data' => $this->actions->update($group, $data)]);
    }

    public function destroy(RefServiceGroup $group)
    {
        $this->actions->destroy($group);
        return response()->noContent();
    }

    private static function rules(?int $id = null, bool $sometimes = false): array
    {
        $req = $sometimes ? ['sometimes', 'required'] : ['required'];
        return [
            'name'        => array_merge($req, ['string', 'max:191']),
            'slug'        => array_merge($req, ['string', 'max:191', $id ? Rule::unique('ref_service_groups', 'slug')->ignore($id) : 'unique:ref_service_groups,slug']),
            'description' => ['nullable', 'string'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }
}
