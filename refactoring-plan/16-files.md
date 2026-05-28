# Step 16 — Files (upload + download bridge)

Many SPA forms attach files (driver license scans, medical card, insurance docs, request attachments). Centralise upload/download into one endpoint family so every form uses the same flow.

## Endpoints (`/api/v1/files`)
| Method | Path                          | Notes                                  |
|--------|-------------------------------|----------------------------------------|
| POST   | `/`                           | multipart upload → returns `{ id, url, name, size, mime }` |
| GET    | `/{file}`                     | metadata                               |
| GET    | `/{file}/download`            | streamed download (`Content-Disposition: attachment`) |
| DELETE | `/{file}`                     | remove (only if owner / admin)         |

Middleware: `auth:sanctum`. File ownership check in `FilePolicy`.

## Controller
`FileApiController` — port of existing `FileController` but JSON-based and signed/streamed downloads.

## FormRequest
`UploadFileRequest`:
- `file|required|file|max:20480` (20 MB cap, tune per env via config).
- `tag|nullable|string` — categorises the file (license, medical_card, insurance, …).

## Resource
`FileResource` — id, name, mime, size, url, download_url, created_at, tags.

## How forms use it
Two-step pattern: SPA uploads each file first → receives `file_id` → form payload references ids only. Backend save endpoints accept `file_ids[]` (or specific keys like `license_file_id`) instead of `multipart/form-data` for the form itself.

This avoids multipart sub-resource updates and keeps every save endpoint pure JSON.

## SPA pieces
- `composables/useFileUpload.ts` — wraps PrimeVue `FileUpload` (custom-upload mode), posts to `/api/v1/files`, returns reactive list of `{ id, status, error }`.
- `<FilePickerField />` component used inside every form that needs files.

## Tests — `tests/Feature/Api/V1/Files/`
- `UploadTest` — multipart upload → 201 + file row + storage put (`Storage::fake`).
- `DownloadTest` — owner can download; foreign user → 403.
- `OversizeRejectedTest` — > cap → 422.
- `DestroyTest` — owner removes; foreign user → 403.

## Done when
- All forms in steps 03/06/07/09/10 reference uploaded `file_id`s rather than re-uploading per save.
- `php artisan test tests/Feature/Api/V1/Files` green.
