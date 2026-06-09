<?php

namespace App\Http\Controllers\Api\V1\Admin\Companies;

use App\Actions\Api\V1\Admin\Companies\AdminCompanyActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\Companies\StoreCompanyRequest;
use App\Http\Requests\Api\V1\Admin\Companies\UpdateCompanyRequest;
use App\Http\Resources\V1\Admin\Companies\AdminCompanyResource;
use App\Models\UserCompany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCompanyController extends Controller
{
    public function __construct(protected AdminCompanyActions $actions) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return AdminCompanyResource::collection($this->actions->index($request));
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        return (new AdminCompanyResource($this->actions->store($request->validated())))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, UserCompany $company): JsonResource
    {
        return new AdminCompanyResource($this->actions->show($company));
    }

    public function update(UpdateCompanyRequest $request, UserCompany $company): JsonResource
    {
        return new AdminCompanyResource($this->actions->update($company, $request->validated()));
    }

    public function destroy(Request $request, UserCompany $company): Response
    {
        $this->actions->destroy($company);

        return response()->noContent();
    }
}
