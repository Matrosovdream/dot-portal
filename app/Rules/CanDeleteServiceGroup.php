<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\ServiceGroups;

class CanDeleteServiceGroup implements Rule
{
    protected $message;

    public function passes($attribute, $value)
    {
        $group = ServiceGroups::find($value);

        if (!$group) {
            $this->message = 'Group cannot be deleted';
            return false;
        }

        // Custom rule: prevent deletion if linked service exists
        if ($group->service()->exists()) {
            $this->message = 'Cannot delete group linked to a service.';
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message ?? 'Invalid group.';
    }
}

