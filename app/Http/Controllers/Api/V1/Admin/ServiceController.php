<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Api\V1\Admin\ServiceActions;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function __construct(protected ServiceActions $actions) {}

    public function index(Request $request)
    {
        return response()->json($this->actions->index($request));
    }

    public function store(Request $request)
    {
        $data = $request->validate(self::rules());
        return response()->json(['data' => $this->actions->store($data)], 201);
    }

    public function show(Service $service)
    {
        return response()->json(['data' => $this->actions->show($service)]);
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate(self::rules($service->id, sometimes: true));
        return response()->json(['data' => $this->actions->update($service, $data)]);
    }

    public function destroy(Service $service)
    {
        $this->actions->destroy($service);
        return response()->noContent();
    }

    public function updateStatus(Request $request, Service $service)
    {
        $data = $request->validate(['status_id' => ['required', 'integer']]);
        return response()->json(['data' => $this->actions->updateStatus($service, (int) $data['status_id'])]);
    }

    private static function rules(?int $id = null, bool $sometimes = false): array
    {
        $req = $sometimes ? ['sometimes', 'required'] : ['required'];
        return [
            'name'        => array_merge($req, ['string', 'max:191']),
            'slug'        => array_merge($req, ['string', 'max:191', $id ? Rule::unique('services', 'slug')->ignore($id) : 'unique:services,slug']),
            'description' => ['nullable', 'string'],
            'is_paid'     => ['nullable', 'boolean'],
            'price'       => ['nullable', 'numeric', 'min:0'],
            'price_title' => ['nullable', 'string', 'max:191'],
            'status_id'   => ['nullable', 'integer'],
            'group_id'    => ['nullable', 'integer'],
            'form_type'   => ['nullable', 'string', 'max:60'],
            'form_id'     => ['nullable', 'integer'],
        ];
    }
}
