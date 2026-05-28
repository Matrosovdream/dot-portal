<?php

namespace App\Actions\Api\V1\Profile;

use App\Models\UserCompany;
use App\Models\UserCompanyAddress;
use Illuminate\Http\Request;

class ProfileCompanyActions
{
    public function show(Request $request): ?UserCompany
    {
        return $request->user()->company()->with(['businessAddress', 'mailingAddress'])->first();
    }

    public function update(Request $request, array $data): UserCompany
    {
        $user = $request->user();

        $companyFields = array_intersect_key($data, array_flip([
            'name', 'phone', 'dot_number', 'mc_number', 'trucks_number', 'drivers_number',
        ]));

        $company = UserCompany::updateOrCreate(
            ['user_id' => $user->id],
            $companyFields,
        );

        foreach (['business', 'mailing'] as $type) {
            if (!empty($data[$type])) {
                UserCompanyAddress::updateOrCreate(
                    ['item_id' => $company->id, 'type' => $type],
                    $data[$type],
                );
            }
        }

        return $company->fresh(['businessAddress', 'mailingAddress']);
    }
}
