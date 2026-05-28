<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Actions\Api\V1\Profile\ProfileCompanyActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Profile\UpdateCompanyRequest;
use App\Http\Resources\V1\Profile\CompanyResource;
use Illuminate\Http\Request;

class ProfileCompanyController extends Controller
{
    public function __construct(protected ProfileCompanyActions $actions) {}

    public function show(Request $request)
    {
        $company = $this->actions->show($request);
        if (!$company) {
            return response()->json(['data' => null]);
        }
        return new CompanyResource($company);
    }

    public function update(UpdateCompanyRequest $request)
    {
        return new CompanyResource($this->actions->update($request, $request->validated()));
    }
}
