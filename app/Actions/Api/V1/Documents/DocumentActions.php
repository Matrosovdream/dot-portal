<?php

namespace App\Actions\Api\V1\Documents;

use App\Models\DriverDocument;
use App\Models\User;
use App\Repositories\File\FileRepo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DocumentActions
{
    public function __construct(protected FileRepo $fileRepo) {}

    /**
     * Aggregated, paginated list of the authenticated user's documents (files).
     *
     * Scoping combines the project convention with the verified legacy DocumentActions:
     *  - admin/manager: see ALL files (project convention).
     *  - driver: only files referenced by their driver_documents (legacy driver branch).
     *  - company/default: files owned via files.user_id == auth->id (legacy non-driver branch).
     *
     * Note: the files table has user_id but NO company_id/company_user_id columns,
     * so the generic company OR-scoping (used for drivers/vehicles) does NOT apply here.
     */
    public function index(Request $request, User $auth)
    {
        $q = $this->fileRepo->getModel()::query();
        $this->applyScope($q, $auth);

        // Optional plain-Eloquent search (Scout is intentionally NOT used in the API).
        if ($search = $request->query('q')) {
            $like = '%'.$search.'%';
            $q->where(function ($w) use ($like) {
                $w->where('filename', 'like', $like)
                  ->orWhere('search_index', 'like', $like);
            });
        }

        $perPage = min(100, (int) $request->query('per_page', 25));
        return $q->latest()->paginate($perPage);
    }

    private function applyScope(Builder $q, User $auth): void
    {
        if ($auth->isAdmin() || $auth->isManager()) {
            return;
        }

        if ($auth->isDriver()) {
            $driver = $auth->driver;
            $fileIds = $driver
                ? DriverDocument::where('driver_id', $driver->id)->pluck('file_id')->all()
                : [];
            $q->whereIn('id', $fileIds);
            return;
        }

        // company / default: ownership is purely files.user_id
        $q->where('user_id', $auth->id);
    }
}
