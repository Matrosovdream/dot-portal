<?php

namespace App\Actions\Api\V1\Admin;

use App\Models\ReferenceFormField;
use App\Repositories\References\RefFormFieldRepo;
use Illuminate\Http\Request;

class ServiceFieldActions
{
    public function __construct(protected RefFormFieldRepo $repo) {}

    public function index(Request $request)
    {
        $perPage = min(100, (int) $request->query('per_page', 50));
        return $this->repo->getModel()::query()->paginate($perPage);
    }

    public function store(array $data): ReferenceFormField
    {
        return $this->repo->getModel()::create($data);
    }

    public function show(ReferenceFormField $field): ReferenceFormField { return $field; }

    public function update(ReferenceFormField $field, array $data): ReferenceFormField
    {
        $field->update($data);
        return $field->fresh();
    }

    public function destroy(ReferenceFormField $field): void { $field->delete(); }
}
