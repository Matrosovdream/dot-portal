<?php

namespace App\Http\Resources\V1\Documents;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DocumentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => (int) $this->id,
            'type'       => $this->type,
            'name'       => $this->filename,
            'extension'  => $this->extension,
            // size is stored as a STRING column; expose the raw value plus an int cast.
            'size'       => $this->size,
            'size_bytes' => $this->size !== null ? (int) $this->size : null,
            'url'        => $this->resolveUrl(),
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
            // Owning account — surfaced to admin/manager cross-user views.
            'owner' => $this->whenLoaded('user', fn () => $this->user ? [
                'id'       => $this->user->id,
                'fullname' => $this->user->fullname,
                'email'    => $this->user->email,
            ] : null),
        ];
    }

    /**
     * Resolve a public URL for the file without making any network call.
     *
     * The files table stores only path + disk (no url column/accessor). The
     * file.show/file.download named routes live on the web stack, not the API,
     * so route() is intentionally avoided here to prevent RouteNotFoundException.
     * Storage::url() is offline but throws on disks lacking a configured 'url'
     * key (e.g. the default 'local' disk), so we only call it when the disk
     * advertises a url, otherwise we fall back to the raw stored path.
     */
    protected function resolveUrl(): ?string
    {
        if (empty($this->path)) {
            return null;
        }

        $disk = $this->disk ?: config('filesystems.default');

        if (config("filesystems.disks.{$disk}.url")) {
            return Storage::disk($disk)->url($this->path);
        }

        return $this->path;
    }
}
