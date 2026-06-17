<?php

namespace App\Services\Driver;

use App\Models\Driver;
use App\Repositories\Driver\DriverAddressRepo;
use App\Repositories\Driver\DriverCdlLicenseRepo;
use App\Repositories\Driver\DriverDrugTestRepo;
use App\Repositories\Driver\DriverLicenseRepo;
use App\Repositories\Driver\DriverMedicalCardRepo;
use App\Repositories\Driver\DriverMvrRepo;
use Illuminate\Support\Facades\DB;

/**
 * Lets a driver persist their own full driver file (employment + license + CDL +
 * medical card + drug test + MVR + address) in one transaction.
 *
 * Each sub-entity is hasOne-per-driver and upserted by driver_id — EXCEPT the
 * address, which the schema keys by item_id. A section is only written when it
 * carries at least one real value, so a partly-filled form never creates empty rows.
 */
class DriverProfileService
{
    public function __construct(
        protected DriverLicenseRepo     $licenseRepo,
        protected DriverCdlLicenseRepo  $cdlRepo,
        protected DriverMedicalCardRepo $medicalRepo,
        protected DriverDrugTestRepo    $drugTestRepo,
        protected DriverMvrRepo         $mvrRepo,
        protected DriverAddressRepo     $addressRepo,
    ) {}

    public function update(Driver $driver, array $data): void
    {
        DB::transaction(function () use ($driver, $data) {
            // Employment writes the already-existing drivers row, so an all-empty payload is a
            // legitimate "clear every employment field" intent — not a reason to skip the write
            // (unlike the hasOne sub-entities below, where the all-empty guard prevents blank rows).
            if (array_key_exists('employment', $data) && is_array($data['employment'])) {
                // Model update (not a raw query) so Driver::booted keeps the search index in sync.
                $driver->update($data['employment']);
            }

            $this->upsert($this->licenseRepo,  ['driver_id' => $driver->id], $this->section($data, 'license'));
            $this->upsert($this->cdlRepo,      ['driver_id' => $driver->id], $this->section($data, 'cdl'));
            $this->upsert($this->medicalRepo,  ['driver_id' => $driver->id], $this->section($data, 'medical'));
            $this->upsert($this->drugTestRepo, ['driver_id' => $driver->id], $this->section($data, 'drug_test'));
            $this->upsert($this->mvrRepo,      ['driver_id' => $driver->id], $this->section($data, 'mvr'));
            // driver_address links by item_id, NOT driver_id.
            $this->upsert($this->addressRepo,  ['item_id'   => $driver->id], $this->section($data, 'address'));
        });
    }

    /** Return the section payload only when it carries at least one real value. */
    private function section(array $data, string $key): ?array
    {
        $section = $data[$key] ?? null;
        if (! is_array($section)) {
            return null;
        }
        $filled = array_filter($section, fn ($v) => $v !== null && $v !== '');

        return $filled === [] ? null : $section;
    }

    private function upsert($repo, array $key, ?array $values): void
    {
        if ($values === null) {
            return;
        }

        $repo->getModel()->newQuery()->updateOrCreate($key, $values);
    }
}
