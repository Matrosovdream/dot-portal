# Step 03 — User Profile & Company

JSON port of every `dashboard.profile.*` route. The same Action (`ProfileCompanyActions`) does the work — the controller is the thin layer that swaps Blade for JSON.

## Endpoints (`/api/v1/profile`)
| Method | Path                              | Returns / Accepts                          |
|--------|-----------------------------------|--------------------------------------------|
| GET    | `/`                               | user + company + address (current view-model) |
| PUT    | `/`                               | personal fields                            |
| GET    | `/edit`                           | edit form references (states, types, etc.) |
| PUT    | `/password`                       | `current_password`, `password`, confirmation |
| PUT    | `/address`                        | address fields                             |
| GET    | `/company`                        | company view + form refs                   |
| PUT    | `/company`                        | company fields                             |
| GET    | `/driver-license`                 | license + files                            |
| PUT    | `/driver-license`                 | license fields + file uploads              |
| GET    | `/medical-card`                   | medical card + files                       |
| PUT    | `/medical-card`                   | medical card fields                        |

Middleware: `auth:sanctum`, `user.isActive`, `hasRole:driver,company`.

## Controller
`App\Http\Controllers\Api\V1\Profile\ProfileController` — methods mirror old `ProfileCompanyController` 1:1 but each returns:
```php
return new ProfileResource($this->actions->profilePreview());
```

## FormRequests
- `UpdateProfileRequest` — names, email (unique except self), phone, dob
- `UpdatePasswordRequest` — `current_password` rule, `password|confirmed|min:8`
- `UpdateAddressRequest`  — address1, address2, city, state_id, zip (required)
- `UpdateCompanyRequest`  — usdot, ein, legal_name, dba, phone, address (mirror existing fields)
- `UpdateDriverLicenseRequest` — endorsement_id, license_number, expiration_date, type_id, state_id, files
- `UpdateMedicalCardRequest`   — examiner_name, national_registry, issue_date, expiration_date, files

## Resources
- `ProfileResource` — flattens user + address into the shape the SPA edit screen needs.
- `CompanyResource` — company + companyAddress + saferweb summary.
- `DriverLicenseResource`, `MedicalCardResource`.

## SPA pieces
- Store: `stores/profile.ts` with `load()`, `updatePersonal(payload)`, `updateAddress(payload)`, `updateCompany(payload)`, `updateLicense(payload)`, `updateMedical(payload)`, `changePassword(payload)`.
- Views:
  - `views/profile/ProfileView.vue` — tabbed: Personal / Address / Company / License / Medical / Security.
  - PrimeVue `TabView`, `Calendar`, `Dropdown`, `FileUpload`.
- File fields: upload to `/api/v1/files` (step 16) then store returned `file_id` in payload.

## Tests — `tests/Feature/Api/V1/Profile/ProfileTest.php`
- `test_get_profile_returns_full_shape` — assertJsonStructure with all keys.
- `test_update_profile_persists_changes` — PUT, fetch, assert.
- `test_update_password_requires_current` — wrong current → 422.
- `test_update_address_requires_zip` — missing zip → 422.
- `test_company_update_persists` — PUT, fetch, assert.
- `test_driver_license_update_with_file` — multipart upload, assert file linked.
- `test_role_gate` — admin user → 403 (this is a user/driver/company surface).

## Done when
- All `/api/v1/profile/*` endpoints return resource JSON.
- Old `dashboard.profile.*` routes still exist (deleted at step 17) but the SPA already drives profile editing.
- `php artisan test tests/Feature/Api/V1/Profile` green.
