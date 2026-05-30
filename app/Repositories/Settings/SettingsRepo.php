<?php

namespace App\Repositories\Settings;

use App\Models\SiteSettings;
use App\Repositories\AbstractRepo;

class SettingsRepo extends AbstractRepo
{
    public function __construct(SiteSettings $model)
    {
        $this->model = $model;
    }
}
