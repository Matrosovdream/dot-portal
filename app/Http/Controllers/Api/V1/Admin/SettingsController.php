<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Api\V1\Admin\SettingsActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\UpdateSettingsRequest;
use App\Http\Resources\V1\Admin\SettingsResource;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(
        private SettingsActions $actions,
    ) {
    }

    public function index(Request $request): SettingsResource
    {
        return new SettingsResource(
            $this->actions->index($request->user())
        );
    }

    public function update(UpdateSettingsRequest $request): SettingsResource
    {
        return new SettingsResource(
            $this->actions->upsert($request->validated(), $request->user())
        );
    }
}
