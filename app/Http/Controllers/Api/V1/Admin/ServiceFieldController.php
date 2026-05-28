<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Api\V1\Admin\ServiceFieldActions;
use App\Http\Controllers\Controller;
use App\Models\ReferenceFormField;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceFieldController extends Controller
{
    public function __construct(protected ServiceFieldActions $actions) {}

    public function index(Request $request)
    {
        return response()->json($this->actions->index($request));
    }

    public function store(Request $request)
    {
        $data = $request->validate(self::rules());
        return response()->json(['data' => $this->actions->store($data)], 201);
    }

    public function show(ReferenceFormField $field)
    {
        return response()->json(['data' => $this->actions->show($field)]);
    }

    public function update(Request $request, ReferenceFormField $field)
    {
        $data = $request->validate(self::rules($field->id, sometimes: true));
        return response()->json(['data' => $this->actions->update($field, $data)]);
    }

    public function destroy(ReferenceFormField $field)
    {
        $this->actions->destroy($field);
        return response()->noContent();
    }

    private static function rules(?int $id = null, bool $sometimes = false): array
    {
        $req = $sometimes ? ['sometimes', 'required'] : ['required'];
        return [
            'title'         => array_merge($req, ['string', 'max:191']),
            'slug'          => array_merge($req, ['string', 'max:191', $id ? Rule::unique('ref_form_fields', 'slug')->ignore($id) : 'unique:ref_form_fields,slug']),
            'entity'        => array_merge($req, ['string', 'max:60']),
            'type'          => ['nullable', 'string', 'max:60'],
            'section'       => ['nullable', 'string', 'max:60'],
            'placeholder'   => ['nullable', 'string', 'max:191'],
            'tooltip'       => ['nullable', 'string'],
            'description'   => ['nullable', 'string'],
            'default_value' => ['nullable', 'string', 'max:191'],
            'reference_code'=> ['nullable', 'string', 'max:60'],
            'icon'          => ['nullable', 'string', 'max:60'],
            'classes'       => ['nullable', 'string', 'max:191'],
        ];
    }
}
